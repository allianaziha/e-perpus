<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';
    protected $fillable = [
        'kode_peminjaman',
        'user_id',
        'buku_id',
        'jumlah_buku',
        'tgl_pinjam',
        'tgl_jatuh_tempo',
        'status',
        'is_read'
    ];

    protected $casts = [
        'tgl_pinjam' => 'date',
        'tgl_jatuh_tempo' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class);
    }

    public function perpanjanganRequests()
    {
        return $this->hasMany(PerpanjanganRequest::class);
    }

    public function latestPerpanjanganRequest()
    {
        return $this->hasOne(PerpanjanganRequest::class)->latest();
    }

}
