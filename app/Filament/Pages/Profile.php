<?php

namespace App\Filament\Pages;

use App\Enums\UserGender;
use App\Models\Country;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Phpsa\FilamentPasswordReveal\Password;
use Ysfkaya\FilamentPhoneInput\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Account';

    protected static ?int $navigationSort = 0;

    protected static string $view = 'filament.pages.profile';

    public string $first_name;

    public string $last_name;

    public string $email;

    public string $phone;

    public ?string $birth_date = null;

    public ?string $gender = null;

    public array|string|null $avatar = null;

    public ?string $current_password = null;

    public ?string $new_password = null;

    public ?string $new_password_confirmation = null;

    public function mount()
    {
        $this->form->fill([
            'first_name' => auth()->user()->first_name,
            'last_name' => auth()->user()->last_name,
            'email' => auth()->user()->email,
            'phone' => auth()->user()->phone,
            'birth_date' => auth()->user()->birth_date,
            'gender' => auth()->user()->gender,
            'avatar' => auth()->user()->getFirstMediaUrl('avatars'),
        ]);
    }

    public function submit()
    {
        $this->form->getState();

        $state = array_filter([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,
            'password' => $this->new_password ? Hash::make($this->new_password) : null,
        ]);

        $user = auth()->user();

        if (count($this->avatar)) {
            $user->clearMediaCollection('avatars')
                ->addMedia($this->avatar[array_key_first($this->avatar)])
                ->sanitizingFileName(fn ($fileName) => strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName)))
                ->toMediaCollection('avatars', 'public');
        }

        $user->update($state);

        if ($this->new_password) {
            $this->updateSessionPassword($user);
        }

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        $this->notify('success', 'Your profile has been updated.');
    }

    protected function updateSessionPassword(User $user)
    {
        request()->session()->put([
            'password_hash_' . auth()->getDefaultDriver() => $user->getAuthPassword(),
        ]);
    }

    public function getCancelButtonUrlProperty(): string
    {
        return static::getUrl();
    }

    protected function getBreadcrumbs(): array
    {
        return [
            url()->current() => 'Profile',
        ];
    }

    protected function getFormSchema(): array
    {
        return [
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
                    DatePicker::make('birth_date')->maxDate(now()),
                    Select::make('gender')->options(UserGender::asSelectArray()),
                    SpatieMediaLibraryFileUpload::make('avatar')->collection('avatars')->columnSpanFull(),
                ]),
            Section::make('Update Password')->columns()
                ->schema([
                    Password::make('current_password')
                        ->password()
                        ->rule('required_with:new_password')
                        ->currentPassword()
                        ->autocomplete('off')
                        ->columnSpan(1)
                        ->revealable()
                        ->copyable(),
                    Grid::make()->schema([
                        Password::make('new_password')
                            ->password()
                            ->rule('confirmed')
                            ->autocomplete('new-password')
                            ->revealable()
                            ->copyable()
                            ->generatable(),
                        Password::make('new_password_confirmation')
                            ->password()
                            ->label('Confirm Password')
                            ->rule('required_with:new_password')
                            ->currentPassword()
                            ->autocomplete('new-password')
                            ->revealable()
                            ->copyable()
                            ->generatable(),
                    ]),
                ]),
        ];
    }
}
