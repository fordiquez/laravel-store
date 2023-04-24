<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StateResource\Pages;
use App\Filament\Resources\StateResource\RelationManagers\CitiesRelationManager;
use App\Models\Country;
use App\Models\State;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class StateResource extends Resource
{
    protected static ?string $model = State::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationGroup = 'Locations';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxlength(50)
                    ->hint(fn ($state, $component) => 'left: ' . $component->getMaxLength() - strlen($state) . ' characters')
                    ->reactive(),
                Forms\Components\TextInput::make('old_name')->maxlength(50),
                Forms\Components\Select::make('country_id')
                    ->required()
                    ->reactive()
                    ->options(Country::all()->pluck('name', 'id')->toArray())
                    ->afterStateUpdated(fn (callable $set) => $set('parent_id', null)),
                Forms\Components\Select::make('parent_id')
                    ->reactive()
                    ->options(fn (callable $get) => State::whereCountryId($get('country_id'))?->pluck('name', 'id')->toArray())
                    ->disabled(fn (callable $get) => !$get('country_id')),
                Forms\Components\TextInput::make('uuid')->required()->uuid()->default(fake()->uuid),
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
                Tables\Columns\TextColumn::make('country.name')->searchable()->sortable()->limit(25)->wrap(),
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
                Tables\Filters\SelectFilter::make('country')->form([
                    Forms\Components\Select::make('country_id')->label('Country')
                        ->options(fn () => Country::all()->pluck('name', 'id')->toArray()),
                    Forms\Components\Select::make('parent_id')->label('Parent State')
                        ->options(fn (callable $get) => Country::find($get('country_id'))?->states->pluck('name', 'id')->toArray())
                        ->disabled(fn (callable $get) => empty($get('country_id'))),
                ])->query(function (Builder $query, array $data) {
                    $countryId = (int) $data['country_id'];
                    $parentId = (int) $data['parent_id'];

                    if (!empty($countryId)) {
                        $query->where('country_id', $countryId);
                    }

                    if (!empty($parentId)) {
                        $query->where('parent_id', $parentId);
                    }
                }),
                Tables\Filters\Filter::make('inactive')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', false))
                    ->label('Only inactive'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    protected static function getNavigationBadge(): ?string
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
            'create' => Pages\CreateState::route('/create'),
            'view' => Pages\ViewState::route('/{record}'),
            'edit' => Pages\EditState::route('/{record}/edit'),
        ];
    }
}
