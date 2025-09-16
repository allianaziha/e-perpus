<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Alert;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderByRaw("
        CASE 
            WHEN role = 'admin' THEN 1 
            WHEN role = 'petugas' THEN 2 
            ELSE 3 
        END
    ")->get();

        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,petugas,user'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        toast('User berhasil dibuat', 'success');
        return redirect()->route('admin.user.index');
    }

     public function show(User $user)
    {
        return view('admin.user.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,petugas,user'
        ]);

        $data = $request->only(['name', 'email', 'role']);
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        toast('User berhasil diupdate', 'info');
        return redirect()->route('admin.user.index');
    }

    public function destroy(User $user)
    {
        $user->delete();
        toast('User berhasil dihapus', 'error');
        return redirect()->route('admin.user.index');
    }
}
