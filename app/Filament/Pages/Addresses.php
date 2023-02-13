<?php

namespace App\Filament\Pages;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Support\Arr;

class Addresses extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Account';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.addresses';

    public ?array $addresses = null;

    public function getCancelButtonUrlProperty(): string
    {
        return static::getUrl();
    }

    protected function getBreadcrumbs(): array
    {
        return [
            url()->current() => 'Addresses',
        ];
    }

    public function mount()
    {
        $this->form->fill([
            'addresses' => auth()->user()->addresses->toArray()
        ]);
    }

    public function submit()
    {
        $this->form->getState();

        $addresses = $this->form->getState()['addresses'];
        $addressesKeys = Arr::pluck($addresses, 'id');
        $deletedAddresses = auth()->user()->addresses()->whereNotIn('id', $addressesKeys)->get();
        $deletedAddresses->each(fn ($item) => $item->delete());

        if (count($addresses)) {
            foreach ($addresses as $address) {
                auth()->user()->addresses()->updateOrCreate([
                    'id' => $address['id'],
                    'user_id' => auth()->user()->id
                ], [
                    'title' => $address['title'],
                    'is_main' => $address['is_main'],
                    'country_id' => $address['country_id'],
                    'state_id' => $address['state_id'],
                    'city_id' => $address['city_id'],
                    'street_id' => $address['street_id'],
                    'house' => $address['house'],
                    'flat' => $address['flat'],
                    'postal_code' => $address['postal_code']
                ]);
            }
        }

        $this->notify('success', 'Your addresses has been updated.');
    }

    protected function getFormSchema(): array
    {
        return [
            Repeater::make('addresses')
                ->schema([
                    TextInput::make('title')->required()->maxLength(255),
                    Toggle::make('is_main')->inline()->default(false),
                    Select::make('country_id')
                        ->options(Country::all()->pluck('name', 'id')->toArray())
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn(callable $set) => $set('state_id', null)),
                    Select::make('state_id')
                        ->options(fn(callable $get) => $get('country_id') ? Country::find($get('country_id'))->states->pluck('name', 'id') : [])
                        ->required()
                        ->reactive()
                        ->disabled(fn(callable $get) => !$get('country_id'))
                        ->afterStateUpdated(fn(callable $set) => $set('city_id', null)),
                    Select::make('city_id')
                        ->options(fn(callable $get) => $get('state_id') ? State::find($get('state_id'))->cities->pluck('name', 'id') : [])
                        ->required()
                        ->reactive()
                        ->disabled(fn(callable $get) => !$get('state_id'))
                        ->afterStateUpdated(fn(callable $set) => $set('street_id', null)),
                    Select::make('street_id')
                        ->options(fn(callable $get) => $get('city_id') ? City::find($get('city_id'))->streets->pluck('name', 'id') : [])
                        ->required()
                        ->reactive()
                        ->disabled(fn(callable $get) => !$get('city_id')),
                    TextInput::make('house')->required(),
                    TextInput::make('flat')->nullable(),
                    TextInput::make('postal_code')->numeric()->nullable(),
                ])->columns()
                ->lazy()
                ->itemLabel(fn (array $state): ?string => $state['title'] ?? null),
        ];
    }
}
