<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Filament\Resources\BrandResource\RelationManagers\GoodsRelationManager;
use App\Models\Brand;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationGroup = 'Goods Management';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\Grid::make()->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(100)
                            ->debounce()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $state, ?Brand $record) {
                                if (!$get('is_slug_changed_manually') && filled($state) && blank($record)) {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        Forms\Components\Hidden::make('is_slug_changed_manually')->default(false)->dehydrated(false),

                        Forms\Components\TextInput::make('slug')
                            ->unique('brands', 'slug', ignoreRecord: true)
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
                    Forms\Components\TextInput::make('url')->label('URL')->maxLength(255),
                    Forms\Components\SpatieMediaLibraryFileUpload::make('logo')->collection('logo'),
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
                Tables\Columns\SpatieMediaLibraryImageColumn::make('logo')->collection('logo')->width(100),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('slug')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('url')->label('URL')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            GoodsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}
