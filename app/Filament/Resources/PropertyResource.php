<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyResource\Pages;
use App\Models\Property;
use Camya\Filament\Forms\Components\TitleWithSlugInput;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-list';

    protected static ?string $navigationGroup = 'Goods Management';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    TitleWithSlugInput::make(
                        fieldTitle: 'name',
                        fieldSlug: 'slug',
                        urlVisitLinkVisible: false,
                        titleLabel: 'Name',
                        slugLabel: 'Slug',
                        slugRuleUniqueParameters: [
                            'table' => 'properties',
                            'column' => 'slug',
                            'ignoreRecord' => true,
                        ]
                    ),
                    Forms\Components\Select::make('category_id')
                        ->required()
                        ->label('Category')
                        ->relationship('category', 'title')
                        ->preload()
                        ->searchable(),
                    Forms\Components\Toggle::make('filterable')->required(),
                ]),
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
                Tables\Columns\TextColumn::make('name')->limit(50)->sortable()->searchable(),
                Tables\Columns\TextColumn::make('slug')->limit(25)->sortable(),
                Tables\Columns\TextColumn::make('category.title')->limit(25)->sortable()->toggleable(),
                Tables\Columns\IconColumn::make('filterable')->boolean()->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable()->toggledHiddenByDefault(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'title')
                    ->multiple()
                    ->searchable(),
                Tables\Filters\TernaryFilter::make('filterable'),
            ])
            ->actions([
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }
}
