<?php

namespace App\Filament\Resources\TransaksiPemasukanResource\Pages;

use App\Filament\Resources\TransaksiPemasukanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransaksiPemasukan extends EditRecord
{
    protected static string $resource = TransaksiPemasukanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
