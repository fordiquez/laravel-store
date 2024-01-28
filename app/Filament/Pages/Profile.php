<?php

namespace App\Filament\Pages;

use App\Enums\UserGender;
use App\Models\Country;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Phpsa\FilamentPasswordReveal\Password;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Account';

    protected static ?int $navigationSort = 0;

    protected static string $view = 'filament.pages.profile';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'first_name' => auth()->user()->first_name,
            'last_name' => auth()->user()->last_name,
            'email' => auth()->user()->email,
            'phone' => auth()->user()->phone,
            'birth_date' => auth()->user()->birth_date,
            'gender' => auth()->user()->gender,
            'avatar' => auth()->user()->getFirstMedia('avatars')?->getPathRelativeToRoot(),
        ]);
    }

    public function save(): void
    {
        try {
            $state = $this->form->getState();

            if ($state['new_password']) {
                $state['password'] = Hash::make($state['new_password']);
            }

            $user = auth()->user();

            if ($state['avatar'] instanceof TemporaryUploadedFile) {
                $user->clearMediaCollection('avatars')
                    ->addMedia($state['avatar'])
                    ->sanitizingFileName(fn ($fileName) => strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName)))
                    ->toMediaCollection('avatars', 'public');
            }

            if (!$state['avatar']) {
                $user->clearMediaCollection('avatars');
            }

            $user->update(Arr::except($state, 'avatar'));

            if ($state['new_password']) {
                $this->updateSessionPassword($user);
            }

            $this->reset(['data.current_password', 'data.new_password', 'data.new_password_confirmation']);

            Notification::make()->title('Your profile has been updated.')->success()->send();

        } catch (Halt $exception) {
            Log::error($exception->getMessage(), $exception->getTrace());

            Notification::make()->title($exception->getMessage())->danger()->send();

            return;
        }
    }

    protected function updateSessionPassword(User $user): void
    {
        request()->session()->put([
            'password_hash_' . auth()->getDefaultDriver() => $user->getAuthPassword(),
        ]);
    }

    public function getCancelButtonUrlProperty(): string
    {
        return static::getUrl();
    }

    public function getBreadcrumbs(): array
    {
        return [
            url()->current() => 'Profile',
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('General')->columns()
                ->schema([
                    TextInput::make('first_name')->required()->maxLength(50),
                    TextInput::make('last_name')->required()->maxLength(50),
                    TextInput::make('email')->email()->required()->unique(User::class, ignorable: auth()->user()),
                    PhoneInput::make('phone')
                        ->rules(['min:9', 'max:13', 'regex:/^([0-9\s\-\+\(\)]*)$/'])
                        ->focusNumberFormat(PhoneInputNumberType::E164)
                        ->initialCountry(Country::DEFAULT_COUNTRY)
                        ->preferredCountries([Country::DEFAULT_COUNTRY])
                        ->onlyCountries(Country::$validCountries)
                        ->formatOnDisplay(false),
                ]),
            Section::make('Details')->columns()
                ->schema([
                    DatePicker::make('birth_date')->native(false)->maxDate(now()),
                    Select::make('gender')->options(UserGender::asSelectArray()),
                    FileUpload::make('avatar')
                        ->visibility(config('filesystems.disks.public.visibility'))
                        ->disk(config('filament.default_filesystem_disk'))
                        ->storeFiles(false)
                        ->maxSize(10240)
                        ->openable()
                        ->previewable()
                        ->downloadable()
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios([
                            null,
                            '16:9',
                            '4:3',
                            '1:1',
                        ])->columnSpanFull(),
                ]),
            Section::make('Update Password')->columns()
                ->schema([
                    Password::make('current_password')
                        ->password()
                        ->requiredWith('new_password')
                        ->currentPassword()
                        ->autocomplete('off')
                        ->columnSpan(1)
                        ->revealable()
                        ->copyable(!app()->isLocal()),
                    Grid::make()->schema([
                        Password::make('new_password')
                            ->password()
                            ->confirmed()
                            ->minLength(6)
                            ->maxLength(25)
                            ->autocomplete('new-password')
                            ->revealable()
                            ->copyable(!app()->isLocal())
                            ->generatable(),
                        Password::make('new_password_confirmation')
                            ->password()
                            ->label('Confirm Password')
                            ->requiredWith('new_password')
                            ->autocomplete('new-password')
                            ->revealable()
                            ->copyable(!app()->isLocal()),
                    ]),
                ]),
        ])->statePath('data');
    }
}
