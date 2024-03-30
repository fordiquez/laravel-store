<?php

namespace App\Filament\Pages;

use App\Models\Country;
use App\Models\State;
use App\Models\UserAddress;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Arr;

class Addresses extends Page implements HasForms
{
    use HasPageShield;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.addresses';

    public ?array $addresses = [];

    public function getCancelButtonUrlProperty(): string
    {
        return static::getUrl();
    }

    public function getBreadcrumbs(): array
    {
        return [
            url()->current() => 'Addresses',
        ];
    }

    public function mount(): void
    {
        $this->form->fill([
            'addresses' => auth()->user()->addresses->toArray(),
        ]);
    }

    public function save(): void
    {
        $addresses = collect($this->form->getState()['addresses']);

        UserAddress::whereNotIn('id', $addresses->pluck('id'))->each(fn (UserAddress $address) => $address->delete());

        $addresses->filter(fn (array $address) => !empty($address['id']))
            ->each(fn (array $address) => UserAddress::find($address['id'])->update(Arr::except($address, ['id', 'created_at', 'updated_at'])));

        $addresses->filter(fn (array $address) => empty($address['id']))
            ->each(fn (array $address) => UserAddress::create(Arr::except($address, 'id')));

        Notification::make()->title('Your addresses has been updated.')->success()->send();
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
            Section::make()->schema([
                Repeater::make('addresses')
                    ->schema([
                        Hidden::make('id'),
                        Hidden::make('user_id')->default(auth()->id()),
                        Select::make('country_id')
                            ->label('Country')
                            ->options(Country::all()->pluck('name', 'id')->toArray())
                            ->required()
                            ->reactive()
                            ->searchable()
                            ->afterStateUpdated(fn (callable $set) => $set('state_id', null)),
                        Select::make('state_id')
                            ->label('State')
                            ->options(fn (Get $get) => Country::find($get('country_id'))?->states->pluck('name', 'id'))
                            ->required()
                            ->reactive()
                            ->searchable()
                            ->disabled(fn (Get $get) => !$get('country_id'))
                            ->afterStateUpdated(fn (Set $set) => $set('city_id', null)),
                        Select::make('city_id')
                            ->label('City')
                            ->options(fn (Get $get) => $get('state_id') ? State::find($get('state_id'))?->cities->pluck('name', 'id') : [])
                            ->required()
                            ->reactive()
                            ->searchable()
                            ->disabled(fn (Get $get) => !$get('state_id')),
                        TextInput::make('street')->required()->maxLength(50),
                        Grid::make()->schema([
                            TextInput::make('house')->required()->maxLength(10),
                            TextInput::make('flat')->nullable()->maxLength(10),
                            TextInput::make('postal_code')->nullable()->numeric()->maxLength(10),
                        ])->columns(3),
                        Toggle::make('is_main')->inline()->default(false),
                    ])->columns()
                    ->lazy()
                    ->maxItems(10)
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null),
            ]),
        ])->statePath('addresses');
    }
}
