<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StateResource\Pages;
use App\Filament\Resources\StateResource\RelationManagers\CitiesRelationManager;
use App\Models\State;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StateResource extends Resource
{
    protected static ?string $model = State::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationGroup = 'Locations';

    protected static ?int $navigationSort = 4;

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('country.name')->searchable()->sortable()->badge(),
                Tables\Columns\TextColumn::make('name')
                    ->limit(50)
                    ->tooltip(fn (Tables\Columns\TextColumn $column) => strlen($column->getState()) <= $column->getCharacterLimit() ? null : $column->getState())
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cities_count')->counts('cities')->label('Cities')->badge()->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean()->toggleable()
                    ->tooltip('Toggle value')
                    ->action(fn (State $record, Tables\Columns\Column $column) => $record->update([$column->getName() => !$record->is_active])),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('country')->relationship('country', 'name')->searchable()->multiple(),
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }

    public static function getRelations(): array
    {
        return [
            CitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStates::route('/'),
            'view' => Pages\ViewState::route('/{record}'),
        ];
    }
}
