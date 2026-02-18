<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'santri_id',
        'category',
        'type',
        'status',
        'rejection_note',
        'mime_type',
        'path',
        'original_name',
    ];

    protected function casts(): array
    {
        return [
            'status'   => 'string',
            'category' => 'string',
        ];
    }

    // Relasi balik ke Santri
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    // Scope: filter per kategori (dipakai di dashboard berkas)
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    // Scope: filter per status (dipakai di admin review)
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    // Helper: cek apakah dokumen sudah diapprove
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    // Helper: cek apakah dokumen ditolak
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}