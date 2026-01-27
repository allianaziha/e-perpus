<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartPinjam extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'buku_id',
        'qty',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke buku
    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}
