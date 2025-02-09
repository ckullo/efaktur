<?php

namespace App\Filament\Resources\FakturSalesResource\Pages;

use App\Filament\Resources\FakturSalesResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageFakturSales extends ManageRecords
{
    protected static string $resource = FakturSalesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
