<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\OrderRecipient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class OrderRecipientsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderRecipients';

    protected static ?string $recordTitleAttribute = 'phone';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('title')->required()->maxLength(255),
                    Forms\Components\TextInput::make('first_name')->required()->maxLength(50),
                    Forms\Components\TextInput::make('last_name')->required()->maxLength(50),
                    Forms\Components\TextInput::make('second_name')->nullable()->maxLength(50),
                    PhoneInput::make('phone')
                        ->required()
                        ->rules(['min:9', 'max:13', 'regex:/^([0-9\s\-\+\(\)]*)$/'])
                        ->focusNumberFormat(PhoneInputNumberType::E164)
                        ->formatOnDisplay(false),
                    Forms\Components\TextInput::make('description')->nullable()->maxLength(255),
                    Forms\Components\Toggle::make('is_default')->default(false)->inline(),
                ])->columns(),
            ]);
    }

    /**
     * @throws \Exception
     */
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('first_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('last_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('second_name')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('phone')->sortable()->searchable(),
                Tables\Columns\IconColumn::make('is_default')
                    ->boolean()
                    ->toggleable()
                    ->tooltip('Toggle value')
                    ->action(function (OrderRecipient $record, Tables\Columns\Column $column) {
                        OrderRecipient::where('id', '!=', $record->id)
                            ->whereUserId($record->user_id)
                            ->whereIsDefault(true)
                            ->update(['is_default' => false]);

                        $record->update([$column->getName() => !$record->is_default]);
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->using(function (RelationManager $livewire, array $data): Model {
                    if ($data['is_default']) {
                        OrderRecipient::whereUserId($livewire->ownerRecord->id)->whereIsDefault(true)->update(['is_default' => false]);
                    }

                    return $livewire->getRelationship()->create($data);
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->beforeFormValidated(function (array $data) {
                    dd($data);
                })->using(function (Model $record, array $data): Model {
                    if ($data['is_default']) {
                        OrderRecipient::where('id', '!=', $record->id)
                            ->whereUserId($record->user_id)
                            ->whereIsDefault(true)
                            ->update(['is_default' => false]);
                    }
                    $record->update($data);

                    return $record;
                }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
