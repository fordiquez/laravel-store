<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Enums\UserGender;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class UsersOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Number of users', User::count()),
            Card::make('Men', User::whereGender(UserGender::MALE)->count()),
            Card::make('Women', User::whereGender(UserGender::FEMALE)->count()),
        ];
    }
}
