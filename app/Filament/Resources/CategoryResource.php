<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers\GoodsRelationManager;
use App\Models\Category;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
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
                    Forms\Components\Select::make('parent_id')->relationship('parent', 'title')->searchable()->preload(),
                    Forms\Components\Grid::make()->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(100)
                            ->debounce()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $state, ?Category $record) {
                                if (!$get('is_slug_changed_manually') && filled($state) && blank($record)) {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        Forms\Components\Hidden::make('is_slug_changed_manually')->default(false)->dehydrated(false),

                        Forms\Components\TextInput::make('slug')
                            ->unique('categories', 'slug', ignoreRecord: true)
                            ->required()
                            ->maxLength(100)
                            ->afterStateUpdated(fn (Forms\Set $set) => $set('is_slug_changed_manually', true))
                            ->rules([
                                'alpha_dash:ascii',
                                function ($state) {
                                    return function (string $attribute, $value, Closure $fail) use ($state) {
                                        if ($state !== '/' && (Str::startsWith($value, '/') || Str::endsWith($value, '/'))) {
                                            $fail('Slug cannot starts or ends with slash.');
                                        }
                                    };
                                },
                            ]),
                    ]),
                    Forms\Components\Textarea::make('description')->rows(3)->maxLength(255),
                    Forms\Components\Grid::make()->schema([
                        Forms\Components\Toggle::make('is_active')->required(),
                        Forms\Components\Toggle::make('is_navigational')->required(),
                    ]),
                    Forms\Components\TextInput::make('manual_url')->label('Manual URL')->url()->maxLength(255),
                    Forms\Components\SpatieMediaLibraryFileUpload::make('thumbnail')->collection('thumbnail'),
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
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('thumbnail')->collection('thumbnail')->width(100),
                Tables\Columns\TextColumn::make('parent.title')
                    ->url(fn (Category $record) => $record->parent_id ? route('filament.admin.resources.categories.edit', $record->parent_id) : null, true)
                    ->sortable()
                    ->toggleable()
                    ->badge(),
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('slug')->toggleable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\IconColumn::make('is_navigational')->boolean(),
                Tables\Columns\TextColumn::make('manual_url')->label('Manual URL')->toggleable(isToggledHiddenByDefault: true),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
