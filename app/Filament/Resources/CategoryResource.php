<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers\GoodsRelationManager;
use App\Models\Category;
use Camya\Filament\Forms\Components\TitleWithSlugInput;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Goods Management';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\Select::make('parent_id')
                        ->relationship('parent', 'title'),
                    TitleWithSlugInput::make(
                        fieldTitle: 'title',
                        fieldSlug: 'slug',
                        urlHostVisible: false,
                        titleLabel: 'Title',
                        titleRules: ['required', 'max:100'],
                        slugLabel: 'Slug',
                        slugRules: ['required', 'max:100', 'alpha_dash'],
                        slugRuleUniqueParameters: [
                            'table' => 'categories',
                            'column' => 'slug',
                            'ignoreRecord' => true
                        ]
                    ),
                    Forms\Components\TextInput::make('description')->maxLength(255),
                    Forms\Components\Toggle::make('is_active')->required(),
                    Forms\Components\TextInput::make('manual_url')->url()->maxLength(255),
                    Forms\Components\SpatieMediaLibraryFileUpload::make('thumbnail')->collection('categories')
                ])
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('thumbnail')->collection('categories')->width(100),
                Tables\Columns\TextColumn::make('parent.title')->toggleable(),
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('slug')->toggleable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('manual_url')->toggleable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('parent')
                    ->multiple()
                    ->attribute('parent_id')
                    ->options(fn () => Category::whereHas('subcategories')->get()->pluck('title', 'id')->toArray())
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

    public static function getRelations(): array
    {
        return [
            GoodsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
