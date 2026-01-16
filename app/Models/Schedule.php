<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute; // Import ini WAJIB
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    /**
     * Atribut yang boleh diisi (Mass Assignment)
     * Penting: Masukkan semua nama kolom disini.
     */
    protected $fillable = [
        'batch_name',
        'academic_year',
        'description',
        'start_date',
        'end_date',
        'exam_date',
        'announcement_date',
        'reregistration_date',
        'price',
        'quota',
        'quota_filled', // JANGAN LUPA TAMBAHKAN INI (biar bisa diupdate nanti)
        'is_active'
    ];

    /**
     * Casting tipe data
     * Berguna: Memastikan kolom tanggal jadi object DateTime (Carbon)
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'exam_date' => 'datetime',
            'announcement_date' => 'datetime',
            'reregistration_date' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * ACCESSOR: formattedPrice
     * Berguna: Memformat angka di kolom 'price' menjadi format Rupiah (Rp 300.000)
     */
    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) =>  // <--- FIX: Pakai "fn" (Arrow Function)
            isset($attributes['price'])  // Apakah kolom price ada isinya?
                ? 'Rp ' . number_format($attributes['price'], 0, ',', '.') // Jika ada, format
                : 'Rp -',  // Jika tidak ada, tampilkan "Rp -"
        );
    }
}