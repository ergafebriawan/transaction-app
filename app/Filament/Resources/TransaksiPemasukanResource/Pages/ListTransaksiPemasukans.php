<?php

namespace App\Filament\Resources\TransaksiPemasukanResource\Pages;

use App\Filament\Resources\TransaksiPemasukanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransaksiPemasukans extends ListRecords
{
    protected static string $resource = TransaksiPemasukanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
