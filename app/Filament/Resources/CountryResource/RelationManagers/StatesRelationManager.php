<?php

namespace App\Filament\Resources\CountryResource\RelationManagers;

use App\Models\State;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class StatesRelationManager extends RelationManager
{
    protected static string $relationship = 'states';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required()->maxLength(50),
                Forms\Components\TextInput::make('old_name')->maxLength(50),
                Forms\Components\TextInput::make('uuid')->required()->uuid()->default(fake()->uuid),
                Forms\Components\Select::make('parent_id')
                    ->options(fn (RelationManager $livewire): array => $livewire->ownerRecord->states()->pluck('name', 'id')->toArray())
                    ->searchable(),
                Forms\Components\TextInput::make('type')->nullable()->default('state')->maxLength(25),
                Forms\Components\Toggle::make('is_active')->required()->default(true),
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('uuid')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable()->limit(50)->wrap(),
                Tables\Columns\TextColumn::make('old_name')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('parent.name')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('type')->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean()->toggleable()
                    ->tooltip('Toggle value')
                    ->action(function ($record, $column) {
                        $name = $column->getName();

                        $record->update([
                            $name => !$record->$name,
                        ]);
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('parent_id')
                    ->options(fn (RelationManager $livewire): array => State::whereCountryId($livewire->ownerRecord->id)->pluck('name', 'id')->toArray()),
                Tables\Filters\Filter::make('inactive')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', false))
                    ->label('Only inactive'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
