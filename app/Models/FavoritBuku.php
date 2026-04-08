<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoritBuku extends Model
{
    protected $table = 'favorit_bukus';
    protected $fillable = ['user_id', 'buku_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}
