<?php

namespace App\Filament\Resources\CityResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class StreetsRelationManager extends RelationManager
{
    protected static string $relationship = 'streets';

    protected static ?string $recordTitleAttribute = 'name';

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
                Forms\Components\Hidden::make('city_id')
                    ->default(fn (RelationManager $livewire) => $livewire->ownerRecord->id),
                Forms\Components\TextInput::make('uuid')->required()->uuid()->default(fake()->uuid),
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
            ])
            ->filters([
                //
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
