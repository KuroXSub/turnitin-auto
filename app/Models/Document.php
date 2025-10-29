<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    /**
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ip_address',
        'user_id',
        'original_filename',
        'file_path',
        'resolved_file_path',
        'status',
        'verification_code',
        'admin_notes',
        'checked_at',
        'checked_by_admin_id',
    ];

    /**
     *
     * @var array<string, string>
     */
    protected $casts = [
        'checked_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by_admin_id');
    }
}
