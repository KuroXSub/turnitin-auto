<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArsipDokumenResource\Pages;
use App\Filament\Resources\ArsipDokumenResource\RelationManagers;
use App\Models\ArsipDokumen;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload as ComponentsSpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\MediaLibrary\Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Spatie\MediaLibrary\Filament\Forms\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ArsipDokumenResource extends Resource
{
    protected static ?string $model = ArsipDokumen::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('judul')
                    ->required()
                    ->maxLength(255),
                Textarea::make('deskripsi')
                    ->columnSpanFull(),
                
                // ✅ INI KOMPONEN UNTUK UPLOAD FILE
                ComponentsSpatieMediaLibraryFileUpload::make('dokumen_file')
                    ->collection('arsip') // Nama collection untuk menyimpan file
                    ->label('Upload Dokumen')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')->searchable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
                
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray') // Menambahkan ikon download
                    ->color('info') // Memberi warna biru agar berbeda
                    ->url(function (ArsipDokumen $record) {
                        // Mengambil URL sementara yang aman dari media pertama
                        return $record->getFirstMedia('arsip')?->getTemporaryUrl(now()->addMinutes(5));
                    })
                    ->openUrlInNewTab(), // Membuka link di tab baru
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArsipDokumens::route('/'),
            'create' => Pages\CreateArsipDokumen::route('/create'),
            'edit' => Pages\EditArsipDokumen::route('/{record}/edit'),
        ];
    }
}
