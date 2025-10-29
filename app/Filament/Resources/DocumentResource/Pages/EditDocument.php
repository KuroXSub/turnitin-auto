<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditDocument extends EditRecord
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateDataBeforeSave(array $data): array
    {
        if ($this->record->status == 'pending' && in_array($data['status'], ['checked', 'rejected'])) {
            $data['checked_by_admin_id'] = Auth::id();
            $data['checked_at'] = now();
        }

        return $data;
    }
}
