<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CountryResource\Pages;
use App\Filament\Resources\CountryResource\RelationManagers\StatesRelationManager;
use App\Models\Country;
use Closure;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\HtmlString;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?string $navigationGroup = 'Locations';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    TextInput::make('name')
                        ->suffixAction(
                            fn ($state, Closure $get, Closure $set) => Action::make('search-action')
                                ->icon('heroicon-o-search')
                                ->tooltip('Find country by ISO alpha-2 code and filled data')
                                ->action(function () use ($state, $get, $set) {
                                    if (blank($state)) {
                                        Filament::notify('danger', 'Please enter a country ISO alpha-2 code');

                                        return;
                                    }

                                    try {
                                        $countryData = Http::acceptJson()->withHeaders([
                                            'X-CSCAPI-KEY' => config('services.csc.key'),
                                        ])->get(config('services.csc.url') . $get('name'))->throw()->json();
                                    } catch (RequestException) {
                                        Filament::notify('danger', 'Unable to find the country, please enter a valid ISO alpha-2 code');

                                        return;
                                    }
                                    $set('name', $countryData['name'] ?? null);
                                    $set('capital', $countryData['capital'] ?? null);
                                    $set('iso2', $countryData['iso2'] ?? null);
                                    $set('iso3', $countryData['iso3'] ?? null);
                                    $set('phone_code', $countryData['phonecode'] ?? null);
                                    $set('currency', $countryData['currency'] ?? null);
                                    $set('tld', $countryData['tld'] ?? null);
                                    $set('region', $countryData['region'] ?? null);
                                    $set('subregion', $countryData['subregion'] ?? null);
                                })
                        )
                        ->required()
                        ->maxLength(50)
                        ->placeholder('Country name or country ISO alpha-2 code')
                        ->dehydrateStateUsing(fn ($state) => ucfirst($state))
                        ->helperText(fn () => new HtmlString('<a href="https://www.iban.com/country-codes" target="_blank">Click here if you need to find the country ISO codes</a>')),
                    Forms\Components\TextInput::make('short_name')
                        ->nullable()
                        ->maxLength(25)
                        ->dehydrateStateUsing(fn ($state) => $state ? ucfirst($state) : null),
                    Forms\Components\TextInput::make('capital')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('iso2')
                        ->required()
                        ->unique(Country::class, ignoreRecord: true)
                        ->length(2)
                        ->dehydrateStateUsing(fn ($state) => strtoupper($state)),
                    Forms\Components\TextInput::make('iso3')
                        ->required()
                        ->unique(Country::class, ignoreRecord: true)
                        ->length(3)
                        ->dehydrateStateUsing(fn ($state) => strtoupper($state)),
                    Forms\Components\TextInput::make('phone_code')
                        ->tel()
                        ->required()
                        ->maxLength(10),
                    Forms\Components\TextInput::make('currency')
                        ->required()
                        ->length(3)
                        ->dehydrateStateUsing(fn ($state) => strtoupper($state)),
                    Forms\Components\TextInput::make('tld')
                        ->required()
                        ->length(3)
                        ->dehydrateStateUsing(fn ($state) => strtolower($state)),
                    Forms\Components\Select::make('region')
                        ->required()
                        ->reactive()
                        ->options(Country::getRegions(true))
                        ->afterStateUpdated(fn (callable $set) => $set('subregion', null)),
                    Forms\Components\Select::make('subregion')
                        ->nullable()
                        ->reactive()
                        ->options(fn (callable $get) => $get('region') ? Country::getSubregions($get('region'), true) : Country::getSubregions(withNamedKeys: true))
                        ->disabled(fn (callable $get) => !$get('region')),
                    SpatieMediaLibraryFileUpload::make('flag')->collection('flags')
                        ->when(fn (Page $livewire) => !$livewire instanceof CreateRecord),
                    Forms\Components\Toggle::make('is_active')
                        ->default(true)
                        ->required(),
                ])->columns(),
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
                SpatieMediaLibraryImageColumn::make('flag')->collection('flags'),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('capital')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('iso2')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('iso3')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('currency')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('region')->sortable(),
                Tables\Columns\TextColumn::make('subregion')->sortable()->wrap(),
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
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\Filter::make('inactive')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', false))
                    ->label('Only inactive'),
                Tables\Filters\SelectFilter::make('region')->options(Country::getRegions(true)),
                Tables\Filters\SelectFilter::make('subregion')->options(Country::getSubregions(withNamedKeys: true)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }

    protected static function getNavigationBadge(): ?string
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
            'create' => Pages\CreateCountry::route('/create'),
            'edit' => Pages\EditCountry::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
