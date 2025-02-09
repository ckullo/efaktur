<?php

namespace App\Filament\Resources\FakturMasukkanResource\Pages;

use App\Filament\Resources\FakturMasukkanResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageFakturMasukkan extends ManageRecords
{
    protected static string $resource = FakturMasukkanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
