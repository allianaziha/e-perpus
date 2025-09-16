<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $fillable = [
        'peminjaman_id',
        'tgl_kembali',
        'kondisi',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function denda()
    {
        return $this->hasOne(Denda::class);
    }

    protected static function booted()
    {
        static::created(function ($pengembalian) {
            $pengembalian->peminjaman->update([
                'status' => 'dikembalikan'
            ]);
        });
    }
}


