<?php

namespace App\Filament\Resources\PPNResource\Pages;

use App\Filament\Resources\PPNResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePPNS extends ManageRecords
{
    protected static string $resource = PPNResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
