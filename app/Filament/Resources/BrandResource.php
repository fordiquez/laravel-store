<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Models\Brand;
use Camya\Filament\Forms\Components\TitleWithSlugInput;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationGroup = 'Goods Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    TitleWithSlugInput::make(
                        fieldTitle: 'name',
                        fieldSlug: 'slug',
                        urlHostVisible: false,
                        titleLabel: 'Name',
                        titleRules: ['required', 'max:100'],
                        slugLabel: 'Slug',
                        slugRules: ['required', 'max:100', 'alpha_dash'],
                        slugRuleUniqueParameters: [
                            'table' => 'brands',
                            'column' => 'slug',
                            'ignoreRecord' => true
                        ]
                    ),
                    Forms\Components\TextInput::make('url')->maxLength(255),
                    Forms\Components\SpatieMediaLibraryFileUpload::make('logo')->collection('brands')
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
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('logo')->collection('brands')->width(100),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('slug')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('url')->toggleable(),
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

    public static function getRelations(): array
    {
        return [
            //
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
