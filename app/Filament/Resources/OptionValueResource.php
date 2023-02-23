<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OptionValueResource\Pages;
use App\Models\OptionValue;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class OptionValueResource extends Resource
{
    protected static ?string $model = OptionValue::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments';

    protected static ?string $navigationGroup = 'Goods Management';

    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('option_id')
                    ->relationship('option', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->maxLength(255),
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
                Tables\Columns\TextColumn::make('option.name')->sortable()->limit(50),
                Tables\Columns\TextColumn::make('value')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('option')
                    ->multiple()
                    ->relationship('option', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOptionValues::route('/'),
            'create' => Pages\CreateOptionValue::route('/create'),
            'edit' => Pages\EditOptionValue::route('/{record}/edit'),
        ];
    }
}
