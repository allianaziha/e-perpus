<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::latest()->get();
        return view('user.buku.index', compact('buku'));
    }

    public function show(Buku $buku)
    {
        return view('user.buku.show', compact('buku'));
    }
}
