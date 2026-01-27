<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile'); // 1 halaman profile + form
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('avatar')) {

            // hapus avatar lama (jika ada)
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }

            $file = $request->file('avatar');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = 'uploads/avatar/' . $filename;

            $file->move(public_path('uploads/avatar'), $filename);

            // simpan path lengkap
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->route('profile.show')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function deleteAvatar(Request $request)
    {
        $user = Auth::user();

        if ($user->avatar && file_exists(public_path($user->avatar))) {
            unlink(public_path($user->avatar)); // hapus file fisik
        }

        $user->avatar = null; // reset path avatar di DB
        $user->save();

        return redirect()->route('profile.show')
                        ->with('success', 'Avatar berhasil dihapus.');
    }

}
