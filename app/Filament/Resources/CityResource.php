<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CityResource\Pages;
use App\Models\City;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'Locations';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxlength(50)
//                        ->hint(fn (string $state, $component): string => 'left: ' . $component->getMaxLength() - strlen($state) . ' characters')
                        ->reactive(),
                    Forms\Components\TextInput::make('old_name')->maxlength(50),
                    Forms\Components\Select::make('state_id')
                        ->relationship('state', 'name')
                        ->required()
                        ->searchable(),
                    Forms\Components\TextInput::make('type')->nullable()->default('state')->maxLength(25),
                    Forms\Components\TextInput::make('uuid')->required()->uuid()->default(fake()->uuid),
                    Forms\Components\Toggle::make('is_active')->required()->default(true),
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
                Tables\Columns\TextColumn::make('uuid')->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('state.name')->sortable(),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable()->limit(50)->wrap(),
                Tables\Columns\TextColumn::make('old_name')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('type')->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_state_center')->boolean()->toggleable()
                    ->tooltip('Toggle value')
                    ->action(fn ($record, $column) => static::iconAction($record, $column)),
                Tables\Columns\IconColumn::make('big_city')->boolean()->toggleable()
                    ->tooltip('Toggle value')
                    ->action(fn ($record, $column) => static::iconAction($record, $column)),
                Tables\Columns\IconColumn::make('is_active')->boolean()->toggleable()
                    ->tooltip('Toggle value')
                    ->action(fn ($record, $column) => static::iconAction($record, $column)),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('state')
                    ->relationship('state', 'name')
                    ->searchable()->multiple(),
                Tables\Filters\Filter::make('state_center')
                    ->query(fn (Builder $query): Builder => $query->where('is_state_center', true)),
                Tables\Filters\Filter::make('big_city')
                    ->query(fn (Builder $query): Builder => $query->where('big_city', true)),
                Tables\Filters\Filter::make('inactive')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', false))
                    ->label('Only inactive'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCities::route('/'),
            'create' => Pages\CreateCity::route('/create'),
            'edit' => Pages\EditCity::route('/{record}/edit'),
        ];
    }

    public static function iconAction($record, $column): void
    {
        $name = $column->getName();

        $record->update([
            $name => !$record->$name,
        ]);
    }
}
