<?php

namespace App\Filament\Resources\OptionValueResource\Pages;

use App\Filament\Resources\OptionValueResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOptionValue extends EditRecord
{
    protected static string $resource = OptionValueResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
