<?php

namespace App\Filament\Resources;

use App\Enums\UserGender;
use App\Enums\UserStatus;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\AddressesRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\OrderRecipientsRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\ReviewsRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\SocialsRelationManager;
use App\Filament\Resources\UserResource\Widgets\UsersOverview;
use App\Models\Country;
use App\Models\User;
use BezhanSalleh\FilamentShield\FilamentShield;
use BezhanSalleh\FilamentShield\Support\Utils;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Page;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Phpsa\FilamentPasswordReveal\Password;
use Spatie\Permission\Models\Permission;
use Ysfkaya\FilamentPhoneInput\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 0;

    protected static ?string $recordTitleAttribute = 'email';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Details')->schema([
                TextInput::make('first_name')->required()->minLength(2)->maxLength(50),
                TextInput::make('last_name')->required()->minLength(2)->maxLength(50),
                TextInput::make('email')->email()->required()->unique(User::class, ignoreRecord: true),
                PhoneInput::make('phone')
                    ->rules(['min:9', 'max:13', 'regex:/^([0-9\s\-\+\(\)]*)$/'])
                    ->focusNumberFormat(PhoneInputNumberType::E164)
                    ->initialCountry(Country::DEFAULT_COUNTRY)
                    ->preferredCountries([Country::DEFAULT_COUNTRY])
                    ->onlyCountries(Country::$validCountries)
                    ->formatOnDisplay(false),
                DatePicker::make('birth_date')->maxDate(now()),
                SpatieMediaLibraryFileUpload::make('avatar')->collection('avatars'),
                Select::make('gender')->options(UserGender::asSelectArray()),
                Select::make('status')->options(UserStatus::asSelectArray())->required(),
                Password::make('password')
                    ->password()
                    ->minLength(8)
                    ->revealable()
                    ->copyable()
                    ->generatable()
                    ->required(fn (Page $livewire) => $livewire instanceof CreateRecord)
                    ->same('passwordConfirmation')
                    ->dehydrated(fn ($state) => filled($state))
                    ->dehydrateStateUsing(fn ($state) => bcrypt($state)),
                Password::make('passwordConfirmation')
                    ->password()
                    ->revealable()
                    ->copyable()
                    ->generatable()
                    ->label('Password Confirmation')
                    ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord)
                    ->minLength(8)
                    ->dehydrated(false),
                CheckboxList::make('roles')
                    ->columnSpan('full')
                    ->reactive()
                    ->relationship(
                        'roles',
                        'name',
                        fn (Builder $query) => !auth()->user()->hasRole('super_admin') ?
                            $query->where('name', '<>', 'super_admin') : $query
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) => Str::of($record->name)->headline())
                    ->columns(4),
            ])->columns(),
            Section::make('Permissions')
                ->description('Users with roles have permission to completely manage resources based on the permissions set under the Roles Menu. To limit a user\'s access to specific resources disable thier roles and assign them individual permissions below.')
                ->collapsible()
                ->collapsed()
                ->schema([
                    Tabs::make('Permissions')
                        ->tabs([
                            Tab::make(__('filament-shield::filament-shield.resources'))
                                ->visible(fn (): bool => Utils::isResourceEntityEnabled())
                                ->reactive()
                                ->schema(static::getResourceEntitiesSchema()),
                            Tab::make(__('filament-shield::filament-shield.pages'))
                                ->visible(fn (): bool => Utils::isPageEntityEnabled() && count(FilamentShield::getPages()) > 0)
                                ->reactive()
                                ->schema(static::getPageEntityPermissionsSchema()),
                            Tab::make(__('filament-shield::filament-shield.widgets'))
                                ->visible(fn (): bool => Utils::isWidgetEntityEnabled() && count(FilamentShield::getWidgets()) > 0)
                                ->reactive()
                                ->schema(static::getWidgetEntityPermissionSchema()),
                            Tab::make(__('filament-shield::filament-shield.custom'))
                                ->visible(fn (): bool => Utils::isCustomPermissionEntityEnabled())
                                ->reactive()
                                ->schema(static::getCustomEntitiesPermissionsSchema()),
                        ])
                        ->columnSpan('full'),
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
                SpatieMediaLibraryImageColumn::make('avatar')->collection('avatars'),
                Tables\Columns\TextColumn::make('first_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('last_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->tooltip('Copy to clipboard')
                    ->copyable()
                    ->copyMessage('Email address copied')
                    ->copyMessageDuration(1500)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')->dateTime()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('roles.name')->formatStateUsing(fn ($state) => Str::of($state)->headline()),
                Tables\Columns\TextColumn::make('birth_date')->date()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('gender')->enum(UserGender::getValues())->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('status')->enum(UserStatus::getValues())->sortable(),
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
            AddressesRelationManager::class,
            SocialsRelationManager::class,
            OrderRecipientsRelationManager::class,
            ReviewsRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [UsersOverview::class];
    }

    protected static function getNavigationBadge(): ?string
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

    public static function getResourceEntitiesSchema(): ?array
    {
        return collect(FilamentShield::getResources())->sortKeys()->reduce(function ($entities, $entity) {
            $entities[] = Card::make()
                ->extraAttributes(['class' => 'border-0 shadow-lg dark:bg-gray-900'])
                ->schema([
                    Toggle::make($entity['resource'])
                        ->label(FilamentShield::getLocalizedResourceLabel($entity['fqcn']))
                        ->onIcon('heroicon-s-lock-open')
                        ->offIcon('heroicon-s-lock-closed')
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, Closure $get, $state) use ($entity) {
                            collect(Utils::getGeneralResourcePermissionPrefixes())->each(function ($permission) use ($set, $entity, $state) {
                                $set($permission . '_' . $entity['resource'], $state);
                            });

                            if (!$state) {
                                $set('select_all', false);
                            }

                            static::refreshSelectAllStateViaEntities($set, $get);
                        })
                        ->dehydrated(false),
                    Fieldset::make('Permissions')
                        ->label(__('filament-shield::filament-shield.column.permissions'))
                        ->extraAttributes(['class' => 'text-primary-600 border-gray-300 dark:border-gray-800'])
                        ->columns([
                            'default' => 2,
                            'md' => 3,
                            'lg' => 3,
                            'xl' => 4,
                        ])
                        ->schema(static::getResourceEntityPermissionsSchema($entity)),
                ])
                ->columns()
                ->columnSpan(1);

            return $entities;
        }, collect())
            ->toArray();
    }

    public static function getResourceEntityPermissionsSchema($entity): ?array
    {
        return collect(Utils::getGeneralResourcePermissionPrefixes())->reduce(function ($permissions/** @phpstan ignore-line */, $permission) use ($entity) {
            $permissions[] = Checkbox::make($permission . '_' . $entity['resource'])
                ->label(FilamentShield::getLocalizedResourcePermissionLabel($permission))
                ->extraAttributes(['class' => 'text-primary-600'])
                ->afterStateHydrated(function (Closure $set, Closure $get, $record) use ($entity, $permission) {
                    if (is_null($record)) {
                        return;
                    }

                    $set($permission . '_' . $entity['resource'], $record->checkPermissionTo($permission . '_' . $entity['resource']));

                    static::refreshResourceEntityStateAfterHydrated($record, $set, $entity['resource']);

                    static::refreshSelectAllStateViaEntities($set, $get);
                })
                ->reactive()
                ->afterStateUpdated(function (Closure $set, Closure $get, $state) use ($entity) {
                    static::refreshResourceEntityStateAfterUpdate($set, $get, Str::of($entity['resource']));

                    if (!$state) {
                        $set($entity['resource'], false);
                        $set('select_all', false);
                    }

                    static::refreshSelectAllStateViaEntities($set, $get);
                })
                ->dehydrated(fn ($state): bool => $state);

            return $permissions;
        }, collect())
            ->toArray();
    }

    protected static function refreshSelectAllStateViaEntities(Closure $set, Closure $get): void
    {
        $entitiesStates = collect(FilamentShield::getResources())
            ->when(Utils::isPageEntityEnabled(), fn ($entities) => $entities->merge(FilamentShield::getPages()))
            ->when(Utils::isWidgetEntityEnabled(), fn ($entities) => $entities->merge(FilamentShield::getWidgets()))
            ->when(Utils::isCustomPermissionEntityEnabled(), fn ($entities) => $entities->merge(static::getCustomEntities()))
            ->map(function ($entity) use ($get) {
                if (is_array($entity)) {
                    return (bool) $get($entity['resource']);
                }

                return (bool) $get($entity);
            });

        if ($entitiesStates->containsStrict(false) === false) {
            $set('select_all', true);
        }

        if ($entitiesStates->containsStrict(false) === true) {
            $set('select_all', false);
        }
    }

    protected static function refreshEntitiesStatesViaSelectAll(Closure $set, $state): void
    {
        collect(FilamentShield::getResources())->each(function ($entity) use ($set, $state) {
            $set($entity['resource'], $state);
            collect(Utils::getGeneralResourcePermissionPrefixes())->each(function ($permission) use ($entity, $set, $state) {
                $set($permission . '_' . $entity['resource'], $state);
            });
        });

        collect(FilamentShield::getPages())->each(function ($page) use ($set, $state) {
            if (Utils::isPageEntityEnabled()) {
                $set($page, $state);
            }
        });

        collect(FilamentShield::getWidgets())->each(function ($widget) use ($set, $state) {
            if (Utils::isWidgetEntityEnabled()) {
                $set($widget, $state);
            }
        });

        static::getCustomEntities()->each(function ($custom) use ($set, $state) {
            if (Utils::isCustomPermissionEntityEnabled()) {
                $set($custom, $state);
            }
        });
    }

    protected static function refreshResourceEntityStateAfterUpdate(Closure $set, Closure $get, string $entity): void
    {
        $permissionStates = collect(Utils::getGeneralResourcePermissionPrefixes())
            ->map(function ($permission) use ($get, $entity) {
                return (bool) $get($permission . '_' . $entity);
            });

        if ($permissionStates->containsStrict(false) === false) {
            $set($entity, true);
        }

        if ($permissionStates->containsStrict(false) === true) {
            $set($entity, false);
        }
    }

    protected static function refreshResourceEntityStateAfterHydrated(Model $record, Closure $set, string $entity): void
    {
        $permissions = $record->getPermissionsViaRoles() ?: $record->permissions;

        $entities = $permissions->pluck('name')
            ->reduce(function ($roles, $role) {
                $roles[$role] = Str::afterLast($role, '_');

                return $roles;
            }, collect())
            ->values()
            ->groupBy(function ($item) {
                return $item;
            })->map->count()
            ->reduce(function ($counts, $role, $key) {
                if ($role > 1 && $role == count(Utils::getGeneralResourcePermissionPrefixes())) {
                    $counts[$key] = true;
                } else {
                    $counts[$key] = false;
                }

                return $counts;
            }, []);

        // set entity's state if one are all permissions are true
        if (Arr::exists($entities, $entity) && Arr::get($entities, $entity)) {
            $set($entity, true);
        } else {
            $set($entity, false);
            $set('select_all', false);
        }
    }

    protected static function getPageEntityPermissionsSchema(): ?array
    {
        return collect(FilamentShield::getPages())->sortKeys()->reduce(function ($pages, $page) {
            $pages[] = Grid::make()
                ->schema([
                    Checkbox::make($page)
                        ->label(FilamentShield::getLocalizedPageLabel($page))
                        ->inline()
                        ->afterStateHydrated(function (Closure $set, Closure $get, $record) use ($page) {
                            if (is_null($record)) {
                                return;
                            }

                            $set($page, $record->checkPermissionTo($page));

                            static::refreshSelectAllStateViaEntities($set, $get);
                        })
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, Closure $get, $state) {
                            if (!$state) {
                                $set('select_all', false);
                            }

                            static::refreshSelectAllStateViaEntities($set, $get);
                        })
                        ->dehydrated(fn ($state): bool => $state),
                ])
                ->columns(1)
                ->columnSpan(1);

            return $pages;
        }, []);
    }

    protected static function getWidgetEntityPermissionSchema(): ?array
    {
        return collect(FilamentShield::getWidgets())->reduce(function ($widgets, $widget) {
            $widgets[] = Grid::make()
                ->schema([
                    Checkbox::make($widget)
                        ->label(FilamentShield::getLocalizedWidgetLabel($widget))
                        ->inline()
                        ->afterStateHydrated(function (Closure $set, Closure $get, $record) use ($widget) {
                            if (is_null($record)) {
                                return;
                            }

                            $set($widget, $record->checkPermissionTo($widget));

                            static::refreshSelectAllStateViaEntities($set, $get);
                        })
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, Closure $get, $state) {
                            if (!$state) {
                                $set('select_all', false);
                            }

                            static::refreshSelectAllStateViaEntities($set, $get);
                        })
                        ->dehydrated(fn ($state): bool => $state),
                ])
                ->columns(1)
                ->columnSpan(1);

            return $widgets;
        }, []);
    }

    protected static function getCustomEntities(): ?Collection
    {
        $resourcePermissions = collect();
        collect(FilamentShield::getResources())->each(function ($entity) use ($resourcePermissions) {
            collect(Utils::getGeneralResourcePermissionPrefixes())->map(function ($permission) use ($resourcePermissions, $entity) {
                $resourcePermissions->push((string) Str::of($permission . '_' . $entity['resource']));
            });
        });

        $entitiesPermissions = $resourcePermissions
            ->merge(FilamentShield::getPages())
            ->merge(FilamentShield::getWidgets())
            ->values();

        return Permission::whereNotIn('name', $entitiesPermissions)->pluck('name');
    }

    protected static function getCustomEntitiesPermissionsSchema(): ?array
    {
        return collect(static::getCustomEntities())->reduce(function ($customEntities, $customPermission) {
            $customEntities[] = Grid::make()
                ->schema([
                    Checkbox::make($customPermission)
                        ->label(Str::of($customPermission)->headline())
                        ->inline()
                        ->afterStateHydrated(function (Closure $set, Closure $get, $record) use ($customPermission) {
                            if (is_null($record)) {
                                return;
                            }

                            $set($customPermission, $record->checkPermissionTo($customPermission));

                            static::refreshSelectAllStateViaEntities($set, $get);
                        })
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, Closure $get, $state) {
                            if (!$state) {
                                $set('select_all', false);
                            }

                            static::refreshSelectAllStateViaEntities($set, $get);
                        })
                        ->dehydrated(fn ($state): bool => $state),
                ])
                ->columns(1)
                ->columnSpan(1);

            return $customEntities;
        }, []);
    }
}
