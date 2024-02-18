<?php

namespace App\Filament\Resources;

use App\Enums\UserGender;
use App\Enums\UserStatus;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\Widgets\UsersOverview;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Phpsa\FilamentPasswordReveal\Password;
use Spatie\Permission\Models\Role;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'General';

    protected static ?int $navigationSort = 0;

    protected static ?string $recordTitleAttribute = 'email';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Details')->schema([
                Forms\Components\TextInput::make('first_name')->required()->minLength(2)->maxLength(50),
                Forms\Components\TextInput::make('last_name')->required()->minLength(2)->maxLength(50),
                Forms\Components\TextInput::make('email')->email()->required()->unique(User::class, ignoreRecord: true),
                Forms\Components\Placeholder::make('email_verified_at')
                    ->label('Email Verified Date')
                    ->hiddenOn('create')
                    ->content(fn (?User $record): string => $record?->email_verified_at?->format('F m, Y H:i:s') ?? '-'),
                PhoneInput::make('phone')
                    ->rules(['min:9', 'max:13', 'regex:/^([0-9\s\-\+\(\)]*)$/'])
                    ->focusNumberFormat(PhoneInputNumberType::E164)
                    ->formatOnDisplay(false),
                Forms\Components\DatePicker::make('birth_date')->native(false)->maxDate(now()),
                Forms\Components\Select::make('gender')->options(UserGender::asSelectArray()),
                Forms\Components\Select::make('status')->options(UserStatus::asSelectArray())->required(),
                Password::make('password')
                    ->password()
                    ->minLength(8)
                    ->revealable()
                    ->copyable(!app()->isLocal())
                    ->generatable()
                    ->required(fn (Page $livewire) => $livewire instanceof CreateRecord)
                    ->same('passwordConfirmation')
                    ->dehydrated(fn ($state) => filled($state))
                    ->dehydrateStateUsing(fn ($state) => bcrypt($state)),
                Password::make('passwordConfirmation')
                    ->password()
                    ->revealable()
                    ->copyable(!app()->isLocal())
                    ->generatable()
                    ->label('Password Confirmation')
                    ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord)
                    ->minLength(8)
                    ->dehydrated(false),
                Forms\Components\SpatieMediaLibraryFileUpload::make('avatar')->collection('avatars')->columnSpanFull(),
                Forms\Components\CheckboxList::make('roles')
                    ->columnSpan('full')
                    ->reactive()
                    ->relationship(
                        'roles',
                        'name',
                        fn (Builder $query) => !auth()->user()->hasRole('super_admin') ?
                            $query->where('name', '<>', 'super_admin') : $query
                    )
                    ->getOptionLabelFromRecordUsing(fn (Role $record) => Str::of($record->name)->headline())
                    ->columns(4),
                Forms\Components\Placeholder::make('created_at')
                    ->label('Created Date')
                    ->hiddenOn('create')
                    ->content(fn (?User $record): string => $record?->created_at?->format('F m, Y H:i:s') ?? '-'),

                Forms\Components\Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->hiddenOn('create')
                    ->content(fn (?User $record): string => $record?->updated_at?->format('F m, Y H:i:s') ?? '-'),
            ])->columns(),
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
                Tables\Columns\SpatieMediaLibraryImageColumn::make('avatar')->collection('avatars'),
                Tables\Columns\TextColumn::make('first_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('last_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->tooltip(!app()->isLocal() ? 'Copy to clipboard' : null)
                    ->copyable(!app()->isLocal())
                    ->copyMessage('Email address copied')
                    ->copyMessageDuration(1500)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('roles.name')->formatStateUsing(fn ($state) => Str::of($state)->headline())
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('birth_date')->date()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('gender')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Str::of($state)->upper())
                    ->color(fn (string $state): string => match ($state) {
                        UserGender::MALE => 'info',
                        UserGender::FEMALE => 'danger'
                    })->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Str::of($state)->upper())
                    ->color(fn (string $state): string => match ($state) {
                        UserStatus::ACTIVE => 'success',
                        UserStatus::INACTIVE => 'gray',
                        UserStatus::BLOCKED => 'danger',
                    })->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\Filter::make('Verified email')
                    ->query(fn (Builder $query): Builder => $query->where('email_verified_at', '!=', null)),
                Tables\Filters\Filter::make('Unverified email')
                    ->query(fn (Builder $query): Builder => $query->where('email_verified_at', null)),
                Tables\Filters\SelectFilter::make('roles')->relationship('roles', 'name'),
                Tables\Filters\SelectFilter::make('gender')->options(UserGender::asSelectArray()),
                Tables\Filters\SelectFilter::make('status')->options(UserStatus::asSelectArray()),
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
            RelationManagers\AddressesRelationManager::class,
            RelationManagers\SocialsRelationManager::class,
            RelationManagers\OrderRecipientsRelationManager::class,
            RelationManagers\ReviewsRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [UsersOverview::class];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
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
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
