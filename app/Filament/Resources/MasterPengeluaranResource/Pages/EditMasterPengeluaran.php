<?php

namespace App\Filament\Resources\MasterPengeluaranResource\Pages;

use App\Filament\Resources\MasterPengeluaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterPengeluaran extends EditRecord
{
    protected static string $resource = MasterPengeluaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
