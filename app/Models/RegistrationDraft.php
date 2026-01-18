<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class RegistrationDraft extends Model
{
    use SoftDeletes;

    /**
     * Aku sengaja pakai fillable biar jelas field apa saja yang boleh diisi.
     * (Kalau pakai guarded sering bikin kebalik dan bikin pusing)
     */
    protected $fillable = [
        'public_id',

        'schedule_id',

        // step 1
        'nama_lengkap',
        'nisn',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',

        // step 2 (ringkas + detail)
        'alamat_lengkap',
        'nama_ibu',
        'no_hp',
        'email',

        'addr_jalan',
        'addr_rt',
        'addr_rw',
        'addr_desa',
        'addr_kec',
        'addr_kab',
        'addr_prov',

        // step 3
        'asal_sekolah',
        'registration_code',

        // ✅ token cookie (token-only)
        'registration_token',
        'registration_token_expires_at',

        'current_step',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'registration_token_expires_at' => 'datetime',
        'current_step' => 'integer',
    ];

    protected static function booted(): void
    {
        /**
         * Aku pastikan public_id selalu terisi otomatis.
         * Ini mencegah error SQL "public_id doesn't have a default value"
         * walaupun controller lupa mengirim field tersebut.
         */
        static::creating(function (RegistrationDraft $draft) {
            if (empty($draft->public_id)) {
                $draft->public_id = (string) Str::uuid();
            }
        });
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Helper: cek token masih valid (ada + belum expired)
     */
    public function isTokenValid(): bool
    {
        if (empty($this->registration_token)) return false;
        if (empty($this->registration_token_expires_at)) return false;

        return Carbon::now()->lt(Carbon::parse($this->registration_token_expires_at));
    }
}
