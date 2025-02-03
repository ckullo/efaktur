<?php

namespace App\Filament\Resources\PPNResource\Pages;

use App\Filament\Resources\PPNResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePPNS extends ManageRecords
{
    protected static string $resource = PPNResource::class;

    public function getTitle(): string
    {
        return 'PPN';
    }
    public function getHeading(): string
    {
        return 'PPN';
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('New PPN'),
        ];
    }

}
