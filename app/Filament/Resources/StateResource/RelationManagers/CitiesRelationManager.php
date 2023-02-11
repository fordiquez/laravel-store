<?php

namespace App\Filament\Resources\StateResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class CitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'cities';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxlength(50)
                    ->hint(fn($state, $component) => 'left: ' . $component->getMaxLength() - strlen($state) . ' characters')
                    ->reactive(),
                Forms\Components\TextInput::make('old_name')->maxlength(50),
                Forms\Components\Hidden::make('state_id')
                    ->default(fn (RelationManager $livewire) => $livewire->ownerRecord->id),
                Forms\Components\TextInput::make('type')->nullable()->default('state')->maxLength(25),
                Forms\Components\TextInput::make('uuid')->required()->uuid()->default(fake()->uuid),
                Forms\Components\Toggle::make('is_active')->required()->default(true)
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
                Tables\Columns\TextColumn::make('type')->sortable()->toggleable(),
                Tables\Columns\IconColumn::make('is_state_center')->boolean()->toggleable()
                    ->tooltip('Toggle value')
                    ->action(fn ($record, $column) => static::iconAction($record, $column)),
                Tables\Columns\IconColumn::make('big_city')->boolean()->toggleable()
                    ->tooltip('Toggle value')
                    ->action(fn ($record, $column) => static::iconAction($record, $column)),
                Tables\Columns\IconColumn::make('is_active')->boolean()->toggleable()
                    ->tooltip('Toggle value')
                    ->action(fn ($record, $column) => static::iconAction($record, $column)),
            ])
            ->filters([
                Tables\Filters\Filter::make('state_center')
                    ->query(fn (Builder $query): Builder => $query->where('is_state_center', true)),
                Tables\Filters\Filter::make('big_city')
                    ->query(fn (Builder $query): Builder => $query->where('big_city', true)),
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

    public static function iconAction($record, $column): void
    {
        $name = $column->getName();

        $record->update([
            $name => !$record->$name
        ]);
    }
}
