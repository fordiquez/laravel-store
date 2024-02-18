<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CountryResource\Pages;
use App\Filament\Resources\CountryResource\RelationManagers\StatesRelationManager;
use App\Models\Country;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?string $navigationGroup = 'Locations';

    protected static ?int $navigationSort = 3;

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                SpatieMediaLibraryImageColumn::make('flag')->collection('flag')->circular()->size(48),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('capital')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('iso2')->label('ISO-2')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('iso3')->label('ISO-3')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('currency')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('region')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('subregion')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('states_count')->counts('states')->label('States')->badge()->sortable(),
                Tables\Columns\TextColumn::make('cities_count')->counts('cities')->label('Cities')->badge()->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean()->toggleable()
                    ->tooltip('Toggle value')
                    ->action(fn (Country $record, Tables\Columns\Column $column) => $record->update([$column->getName() => !$record->is_active])),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('location')
                    ->form([
                        Forms\Components\Select::make('region')
                            ->reactive()
                            ->searchable()
                            ->options(Country::orderBy('region')
                                ->get()
                                ->unique(fn (Country $country) => $country->region)
                                ->map(fn (Country $country) => $country->region)
                                ->filter()
                                ->mapWithKeys(fn (string $region) => [$region => $region])
                                ->toArray()),

                        Forms\Components\Select::make('subregion')
                            ->reactive()
                            ->searchable()
                            ->options(fn (callable $get) => Country::orderBy('region')
                                ->when($get('region'), fn (Builder $query): Builder => $query->where('region', $get('region')))
                                ->get()
                                ->unique(fn (Country $country) => $country->subregion)
                                ->map(fn (Country $country) => $country->subregion)
                                ->filter()
                                ->mapWithKeys(fn (string $subregion) => [$subregion => $subregion])
                                ->toArray()),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['region'], fn (Builder $query): Builder => $query->where('region', $data['region']))
                        ->when($data['subregion'], fn (Builder $query): Builder => $query->where('subregion', $data['subregion'])),
                    )->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['region'] ?? null) {
                            $indicators[] = Indicator::make('Region: ' . $data['region'])->removeField('region');
                        }

                        if ($data['subregion'] ?? null) {
                            $indicators[] = Indicator::make('Subregion: ' . $data['subregion'])->removeField('subregion');
                        }

                        return $indicators;
                    }),
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
            StatesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCountries::route('/'),
            'view' => Pages\ViewCountry::route('/{record}'),
        ];
    }
}
