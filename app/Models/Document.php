<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'santri_id',
        'type',
        'mime_type',
        'path',
        'original_name',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}