<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

    // Security: Whitelist kolom yang boleh diisi massal
    protected $fillable = [
        'user_id',
        'nik',
        'nisn',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat_lengkap',
        'asal_sekolah',
        'status',
    ];

    // Relasi balik ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Documents
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}