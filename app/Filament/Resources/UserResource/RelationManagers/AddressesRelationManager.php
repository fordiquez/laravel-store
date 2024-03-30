<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\UserAddress;
use Filament\Forms\Components;
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
                Components\Grid::make()->schema([
                    Components\Select::make('country_id')
                        ->options(Country::all()->pluck('name', 'id')->toArray())
                        ->required()
                        ->reactive()
                        ->searchable()
                        ->preload()
                        ->afterStateUpdated(fn (callable $set) => $set('state_id', null)),
                    Components\Select::make('state_id')
                        ->options(fn (callable $get) => $get('country_id') ? State::whereCountryId($get('country_id'))->pluck('name', 'id') : [])
                        ->required()
                        ->reactive()
                        ->disabled(fn (callable $get) => !$get('country_id'))
                        ->afterStateUpdated(fn (callable $set) => $set('city_id', null)),
                    Components\Select::make('city_id')
                        ->options(fn (callable $get) => $get('state_id') ? City::whereStateId($get('state_id'))->pluck('name', 'id') : [])
                        ->required()
                        ->reactive()
                        ->disabled(fn (callable $get) => !$get('state_id'))
                        ->afterStateUpdated(fn (callable $set) => $set('street_id', null)),
                ])->columns(3),
                Components\Grid::make()->schema([
                    Components\TextInput::make('street')->required(),
                    Components\TextInput::make('house')->required(),
                    Components\TextInput::make('flat')->nullable(),
                    Components\TextInput::make('postal_code')->numeric()->nullable(),
                ])->columns(4),
                Components\Toggle::make('is_main')->inline()->default(false),
            ])->columns();
    }

    /**
     * @throws \Exception
     */
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\IconColumn::make('is_main')->boolean()->toggleable()
                    ->tooltip('Toggle value')
                    ->action(function (UserAddress $record, Tables\Columns\Column $column) {
                        UserAddress::where('id', '!=', $record->id)
                            ->whereUserId($record->user_id)
                            ->whereIsMain(true)
                            ->update(['is_main' => false]);

                        $record->update([$column->getName() => !$record->is_main]);
                    }),
                Tables\Columns\TextColumn::make('country.name')
                    ->url(fn (UserAddress $record) => route('filament.admin.resources.countries.view', $record->country->iso2), true)
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('state.name')
                    ->url(fn (UserAddress $record) => route('filament.admin.resources.states.view', $record->state_id), true)
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('city.name')
                    ->url(fn (UserAddress $record) => route('filament.admin.resources.cities.view', $record->city_id), true)
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('street')->sortable()->searchable(),
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
