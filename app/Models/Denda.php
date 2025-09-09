<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengembalian_id',
        'jenis',
        'jumlah_hari',
        'nominal',
        'dibayar',
        'status',
    ];

    public function pengembalian()
    {
        return $this->belongsTo(Pengembalian::class);
    }
}
