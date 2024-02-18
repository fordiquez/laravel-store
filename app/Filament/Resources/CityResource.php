<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CityResource\Pages;
use App\Models\City;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'Locations';

    protected static ?int $navigationSort = 5;

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->url(fn (City $record) => route('filament.admin.resources.cities.view', $record->country->iso2), true)
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('state.name')
                    ->url(fn (City $record) => route('filament.admin.resources.states.view', $record->state_id), true)
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('name')
                    ->limit(50)
                    ->tooltip(fn (Tables\Columns\TextColumn $column) => strlen($column->getState()) <= $column->getCharacterLimit() ? null : $column->getState())
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')->boolean()->toggleable()
                    ->tooltip('Toggle value')
                    ->action(fn (City $record, Tables\Columns\Column $column) => $record->update([$column->getName() => !$record->is_active])),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('state')->relationship('state', 'name')->searchable()->multiple(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCities::route('/'),
            'view' => Pages\ViewCity::route('/{record}'),
        ];
    }
}
