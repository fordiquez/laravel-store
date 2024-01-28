<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\Country;
use App\Models\State;
use App\Models\UserAddress;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Toggle::make('is_main')->inline()->default(false),
                    Select::make('country_id')
                        ->options(Country::all()->pluck('name', 'id')->toArray())
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn (callable $set) => $set('state_id', null)),
                    Select::make('state_id')
                        ->options(fn (callable $get) => $get('country_id') ? Country::find($get('country_id'))->states->pluck('name', 'id') : [])
                        ->required()
                        ->reactive()
                        ->disabled(fn (callable $get) => !$get('country_id'))
                        ->afterStateUpdated(fn (callable $set) => $set('city_id', null)),
                    Select::make('city_id')
                        ->options(fn (callable $get) => $get('state_id') ? State::find($get('state_id'))->cities->pluck('name', 'id') : [])
                        ->required()
                        ->reactive()
                        ->disabled(fn (callable $get) => !$get('state_id'))
                        ->afterStateUpdated(fn (callable $set) => $set('street_id', null)),
                    TextInput::make('street')->required(),
                    TextInput::make('house')->required(),
                    TextInput::make('flat')->nullable(),
                    TextInput::make('postal_code')->numeric()->nullable(),
                ]),
            ]);
    }

    /**
     * @throws \Exception
     */
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\IconColumn::make('is_main')->boolean()->toggleable()
                    ->tooltip('Toggle value')
                    ->action(function ($record, $column) {
                        $name = $column->getName();
                        UserAddress::where('id', '!=', $record->id)->whereUserId($record->user_id)->whereIsMain(true)->update([
                            'is_main' => false,
                        ]);
                        $record->update([
                            $name => !$record->$name,
                        ]);
                    }),
                Tables\Columns\TextColumn::make('country.name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('state.name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('city.name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('street.name')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->using(function (RelationManager $livewire, array $data): Model {
                    UserAddress::whereUserId($livewire->ownerRecord->id)->whereIsMain(true)->update([
                        'is_main' => false,
                    ]);

                    return $livewire->getRelationship()->create($data);
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->using(function (Model $record, array $data): Model {
                    UserAddress::where('id', '!=', $record->id)->whereUserId($record->user_id)->whereIsMain(true)->update([
                        'is_main' => false,
                    ]);
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
