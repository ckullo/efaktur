<?php

namespace App\Filament\Resources\MatcodeResource\Pages;

use App\Filament\Resources\MatcodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMatcodes extends ManageRecords
{
    protected static string $resource = MatcodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
