<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Alert;

class UserController extends Controller
{
    public function index()
    {
        $user = User::where('role', 'user')->get();

        $title = 'Hapus user!';
        $text  = 'Apakah anda yakin ingin menghapus user ini?';
        confirmDelete($title, $text);

        return view('petugas.user.index', compact('user'));
    } 

    public function create()
    {
        return view('petugas.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        toast('User berhasil dibuat', 'success');
        return redirect()->route('petugas.user.index');
    }

     public function show(User $user)
    {
        return view('petugas.user.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('petugas.user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $user->id,
        ]);

        $data = $request->only(['name', 'email']);
        
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        toast('User berhasil diupdate', 'info');
        return redirect()->route('petugas.user.index');
    }

    public function destroy(User $user)
    {
        $user->delete();
        toast('User berhasil dihapus', 'error');
        return redirect()->route('petugas.user.index');
    }
}
