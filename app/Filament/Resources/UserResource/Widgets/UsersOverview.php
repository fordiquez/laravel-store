<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Enums\UserGender;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UsersOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Stat::make('Number of users', User::count()),
            Stat::make('Men', User::whereGender(UserGender::MALE)->count()),
            Stat::make('Women', User::whereGender(UserGender::FEMALE)->count()),
        ];
    }
}
