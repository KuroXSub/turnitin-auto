<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Filament\Resources\DocumentResource\RelationManagers;
use App\Models\Document;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Evaluasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Dokumen')
                    ->description('Detail dari pengunggah.')
                    ->schema([
                        TextInput::make('verification_code')
                            ->label('Kode Verifikasi')
                            ->disabled(),
                        TextInput::make('ip_address')
                            ->label('Alamat IP')
                            ->disabled(),
                        TextInput::make('original_filename')
                            ->label('Nama File Asli')
                            ->disabled()
                            ->columnSpanFull(),
                        
                        Placeholder::make('download_original')
                            ->label('Unduh File Asli')
                            ->content(function (?Document $record): HtmlString {
                                if (!$record || !$record->file_path) {
                                    return new HtmlString('<span class="text-sm text-gray-500">File belum diunggah.</span>');
                                }

                                $url = Storage::disk('s3')->temporaryUrl(
                                    $record->file_path,
                                    now()->addMinutes(15)
                                );

                                return new HtmlString("
                                    <a href='{$url}' target='_blank' class='filament-button filament-button-size-sm inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2rem] px-3 text-sm text-gray-800 bg-white border-gray-300 hover:bg-gray-50 focus:ring-primary-600 focus:text-primary-600 focus:bg-primary-50 focus:border-primary-600 dark:bg-gray-800 dark:hover:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:focus:text-primary-400 dark:focus:border-primary-400 dark:focus:bg-gray-800'>
                                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='currentColor' class='w-5 h-5'>
                                            <path d='M10.75 2.75a.75.75 0 00-1.5 0v8.614L6.295 8.235a.75.75 0 10-1.09 1.03l4.25 4.5a.75.75 0 001.09 0l4.25-4.5a.75.75 0 00-1.09-1.03l-2.955 3.129V2.75z' />
                                            <path d='M3.5 12.75a.75.75 0 00-1.5 0v2.5A2.75 2.75 0 004.75 18h10.5A2.75 2.75 0 0018 15.25v-2.5a.75.75 0 00-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5z' />
                                        </svg>
                                        Unduh File Asli
                                    </a>
                                ");
                            })
                            ->columnSpanFull(),
                    ])->columns(2),
                
                Section::make('Proses Admin')
                    ->description('Ubah status, tambahkan catatan, dan unggah file hasil.')
                    ->schema([
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'checked' => 'Checked (Selesai)',
                                'rejected' => 'Rejected (Ditolak)',
                            ])
                            ->required()
                            ->default('pending')
                            ->reactive(),
                        
                        Textarea::make('admin_notes')
                            ->label('Catatan Admin')
                            ->rows(5)
                            ->columnSpanFull(),

                        FileUpload::make('resolved_file_path')
                            ->label('Unggah File Hasil (Checklist/Resolved)')
                            ->disk('s3')
                            ->directory('admin-resolved-files')
                            ->visibility('private')
                            ->columnSpanFull()
                            ->visible(fn ($get) => $get('status') === 'checked'), 
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('original_filename')
                    ->label('Nama File')
                    ->searchable()
                    ->limit(30),
                
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'checked' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('verification_code')
                    ->label('Kode Verifikasi')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Kode disalin!'),

                TextColumn::make('ip_address')
                    ->label('Alamat IP')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('admin.name')
                    ->label('Dicek Oleh')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Waktu Unggah')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('checked_at')
                    ->label('Waktu Selesai')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                // Filter akan ada di 'ListDocuments.php' sebagai Tabs
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }
}
