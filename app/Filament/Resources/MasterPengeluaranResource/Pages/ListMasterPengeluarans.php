<?php

namespace App\Filament\Resources\MasterPengeluaranResource\Pages;

use App\Filament\Resources\MasterPengeluaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterPengeluarans extends ListRecords
{
    protected static string $resource = MasterPengeluaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
