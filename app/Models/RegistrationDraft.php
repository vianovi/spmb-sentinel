<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistrationDraft extends Model
{
    use SoftDeletes;

    // Livi's Rule: Pastikan semua field terdaftar di sini!
    protected $fillable = [
        'schedule_id',
        'nama_lengkap',
        'nisn',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat_lengkap',
        'nama_ibu',
        'no_hp',
        'email',
        'current_step',
    ];

    // Helper untuk casting tanggal otomatis jadi object Carbon
    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // Relasi (Opsional, buat jaga-jaga)
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}