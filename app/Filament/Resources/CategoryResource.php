<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers\GoodsRelationManager;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Goods Management';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\Select::make('parent_id')
                        ->relationship('parent', 'title'),
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(100)
                        ->autofocus()
                        ->live(debounce: 500)
                        ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $state) {
                            if (!$get('is_slug_changed_manually')) {
                                $set('slug', Str::slug($state));
                            }
                        }),
                    Forms\Components\TextInput::make('slug')
                        ->rule('alpha_dash:ascii')
                        ->unique('categories', 'slug', ignoreRecord: true)
                        ->afterStateUpdated(function (Forms\Set $set) {
                            $set('is_slug_changed_manually', true);
                        })
                        ->required()
                        ->maxLength(100),
                    Forms\Components\TextInput::make('description')->maxLength(255),
                    Forms\Components\Toggle::make('is_active')->required(),
                    Forms\Components\Toggle::make('is_navigational')->required(),
                    Forms\Components\TextInput::make('manual_url')->url()->maxLength(255),
                    Forms\Components\SpatieMediaLibraryFileUpload::make('thumbnail')->collection('categories'),
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
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('thumbnail')->collection('categories')->width(100),
                Tables\Columns\TextColumn::make('parent.title')->toggleable(),
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('slug')->toggleable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\IconColumn::make('is_navigational')->boolean(),
                Tables\Columns\TextColumn::make('manual_url')->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('parent')
                    ->multiple()
                    ->attribute('parent_id')
                    ->options(fn () => Category::whereHas('subcategories')->get()->pluck('title', 'id')->toArray()),
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\TernaryFilter::make('is_navigational'),
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

    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
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
