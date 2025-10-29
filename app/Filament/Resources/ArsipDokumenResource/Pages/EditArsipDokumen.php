<?php

namespace App\Filament\Resources\ArsipDokumenResource\Pages;

use App\Filament\Resources\ArsipDokumenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArsipDokumen extends EditRecord
{
    protected static string $resource = ArsipDokumenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
