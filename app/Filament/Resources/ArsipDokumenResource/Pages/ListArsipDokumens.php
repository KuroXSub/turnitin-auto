<?php

namespace App\Filament\Resources\ArsipDokumenResource\Pages;

use App\Filament\Resources\ArsipDokumenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArsipDokumens extends ListRecords
{
    protected static string $resource = ArsipDokumenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
