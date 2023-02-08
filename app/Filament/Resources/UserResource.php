<?php

namespace App\Filament\Resources;

use App\Enums\UserGender;
use App\Enums\UserStatus;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Widgets\UsersOverview;
use App\Models\User;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Phpsa\FilamentPasswordReveal\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $recordTitleAttribute = 'email';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('first_name')->required()->minLength(2)->maxLength(50),
                    TextInput::make('last_name')->required()->minLength(2)->maxLength(50),
                    TextInput::make('email')->email()->required()->unique(User::class, ignoreRecord: true),
                    TextInput::make('phone')->tel()->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'),
                    DatePicker::make('birth_date')->required()->maxDate(now()),
                    SpatieMediaLibraryFileUpload::make('avatar')->collection('avatars'),
                    Select::make('gender')->options(UserGender::asSelectArray())->required(),
                    Select::make('status')->options(UserStatus::asSelectArray())->required(),
                    Password::make('password')
                        ->password()
                        ->minLength(8)
                        ->revealable()
                        ->copyable()
                        ->generatable()
                        ->required(fn(Page $livewire) => $livewire instanceof CreateRecord)
                        ->same('passwordConfirmation')
                        ->dehydrated(fn($state) => filled($state))
                        ->dehydrateStateUsing(fn($state) => bcrypt($state)),
                    Password::make('passwordConfirmation')
                        ->password()
                        ->revealable()
                        ->copyable()
                        ->generatable()
                        ->label('Password Confirmation')
                        ->required(fn(Page $livewire): bool => $livewire instanceof CreateRecord)
                        ->minLength(8)
                        ->dehydrated(false)
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
                SpatieMediaLibraryImageColumn::make('avatar')->collection('avatars'),
                TextColumn::make('first_name')->sortable()->searchable(),
                TextColumn::make('last_name')->sortable()->searchable(),
                TextColumn::make('email')
                    ->copyable()
                    ->copyMessage('Email address copied')
                    ->copyMessageDuration(1500)
                    ->sortable()
                    ->searchable(),
                TextColumn::make('birth_date')->date()->sortable(),
                TextColumn::make('gender')->enum(UserGender::getValues())->sortable(),
                TextColumn::make('status')->enum(UserStatus::getValues())->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Filter::make('Verified email')->query(fn(Builder $query): Builder => $query->where('email_verified_at', '!=', null)),
                Filter::make('Unverified email')->query(fn(Builder $query): Builder => $query->where('email_verified_at', null)),
                SelectFilter::make('gender')->options(UserGender::asSelectArray()),
                SelectFilter::make('status')->options(UserStatus::asSelectArray()),
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
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            UsersOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
