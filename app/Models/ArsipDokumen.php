<?php

namespace App\Models;

use App\Support\CustomPathGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ArsipDokumen extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['judul', 'deskripsi'];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('arsip') // Sesuaikan dengan nama collection Anda
            ->useDisk('s3'); // Definisikan disk secara permanen di sini;
    }
}
