<?php

namespace App\Filament\Resources\BrandResource\RelationManagers;

use App\Enums\GoodStatus;
use App\Models\Good;
use App\Models\Setting;
use Camya\Filament\Forms\Components\TitleWithSlugInput;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GoodsRelationManager extends RelationManager
{
    protected static string $relationship = 'goods';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\Select::make('category_id')
                        ->relationship('category', 'title')
                        ->required()
                        ->searchable(),
                    Forms\Components\Select::make('brand_id')
                        ->relationship('brand', 'name')
                        ->required()
                        ->disabled()
                        ->default(fn(RelationManager $livewire) => $livewire->ownerRecord->id),
                    Forms\Components\TextInput::make('vendor_code')
                        ->required()
                        ->numeric()
                        ->default(rand(1000000000, 2147483647))
                        ->unique(Good::class, 'vendor_code', ignoreRecord: true),
                    TitleWithSlugInput::make(
                        fieldTitle: 'title',
                        fieldSlug: 'slug',
                        urlHostVisible: false,
                        titleLabel: 'Title',
                        titleRules: ['required', 'max:100'],
                        slugLabel: 'Slug',
                        slugRules: ['required', 'max:100', 'alpha_dash'],
                        slugRuleUniqueParameters: [
                            'table' => 'goods',
                            'column' => 'slug',
                            'ignoreRecord' => true
                        ]
                    ),
                    Forms\Components\SpatieMediaLibraryFileUpload::make('thumbnails')
                        ->collection('goods')
                        ->multiple()
                        ->responsiveImages()
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('description')
                        ->maxLength(65535)
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('short_description')
                        ->maxLength(65535)
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('warning_description')
                        ->maxLength(65535)
                        ->columnSpanFull(),
                    Forms\Components\Select::make('tags')
                        ->multiple()
                        ->relationship('tags', 'name')
                        ->preload()
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('old_price')
                        ->numeric()
                        ->suffix(Setting::where('key', 'currency')->value('value')),
                    Forms\Components\TextInput::make('price')
                        ->required()
                        ->numeric()
                        ->suffix(Setting::where('key', 'currency')->value('value')),
                    Forms\Components\TextInput::make('quantity')
                        ->required()->numeric(),
                    Forms\Components\Select::make('status')
                        ->required()
                        ->options(GoodStatus::asSelectArray()),
                ])->columns()

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
                Tables\Columns\TextColumn::make('vendor_code')
                    ->sortable()
                    ->searchable()
                    ->copyable()
                    ->tooltip('Click to copy')
                    ->toggleable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('preview')
                    ->collection('goods')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('category.title')->limit(25)->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('title')->limit(25)->sortable()->searchable(),
                Tables\Columns\TextColumn::make('old_price')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('price')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('quantity')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('status')->sortable()->toggleable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('brand')
                    ->multiple()
                    ->relationship('brand', 'name'),
                Tables\Filters\SelectFilter::make('category')
                    ->multiple()
                    ->relationship('category', 'title'),
                Tables\Filters\SelectFilter::make('status')->options(GoodStatus::asSelectArray())
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
