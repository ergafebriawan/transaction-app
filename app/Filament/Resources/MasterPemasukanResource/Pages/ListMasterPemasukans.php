<?php

namespace App\Filament\Resources\MasterPemasukanResource\Pages;

use App\Filament\Resources\MasterPemasukanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterPemasukans extends ListRecords
{
    protected static string $resource = MasterPemasukanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
