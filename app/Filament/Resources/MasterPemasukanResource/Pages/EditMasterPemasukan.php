<?php

namespace App\Filament\Resources\MasterPemasukanResource\Pages;

use App\Filament\Resources\MasterPemasukanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterPemasukan extends EditRecord
{
    protected static string $resource = MasterPemasukanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
