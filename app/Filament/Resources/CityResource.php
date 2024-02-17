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
                Tables\Columns\TextColumn::make('country.name')->sortable()->badge(),
                Tables\Columns\TextColumn::make('state.name')->sortable()->badge(),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable()->limit(50)->wrap(),
                Tables\Columns\IconColumn::make('is_active')->boolean()->toggleable()
                    ->tooltip('Toggle value')
                    ->action(fn ($record, $column) => $record->update([$column->getName() => !$record->name])),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('state')
                    ->relationship('state', 'name')
                    ->searchable()->multiple(),
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
