<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use App\Models\Document;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListDocuments extends ListRecords
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua')
                ->badge(Document::query()->count()),
            
            'pending' => Tab::make('Antrean (Pending)')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending'))
                ->badge(Document::query()->where('status', 'pending')->count())
                ->badgeColor('warning'),
            
            'checked' => Tab::make('Selesai (Checked)')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'checked'))
                ->badge(Document::query()->where('status', 'checked')->count())
                ->badgeColor('success'),

            'rejected' => Tab::make('Ditolak (Rejected)')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'rejected'))
                ->badge(Document::query()->where('status', 'rejected')->count())
                ->badgeColor('danger'),
        ];
    }
}
