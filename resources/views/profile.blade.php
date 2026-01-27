@extends('layouts.backend')

@section('title', 'Profil')

@section('styles')
<style>
    .profile-wrapper {
        width: 100%;
        max-width: 100%;
        margin: auto;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,.08);
        background: #fff;
        padding: 30px;
    }

    .profile-avatar-wrapper {
        display: flex;
        justify-content: center;
        margin-bottom: 30px;
        position: relative;
    }

    .profile-avatar {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: #4facfe;
        color: #fff;
        font-size: 40px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        object-fit: cover;
    }

    .profile-form .form-group {
        margin-bottom: 16px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <h4 class="mb-4 fw-bold">Profil Saya</h4>

    <div class="profile-wrapper">

        {{-- Avatar --}}
        <div class="profile-avatar-wrapper">
            @if(auth()->user()->avatar)
                <div style="position: relative; display: inline-block;">
                    <img src="{{ asset(auth()->user()->avatar) }}" class="profile-avatar">

                    {{-- Tombol hapus --}}
                    <form action="{{ route('profile.avatar.delete') }}" method="POST" 
                        style="position: absolute; bottom: 0; right: 0;">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm rounded-circle"
                                style="width:28px; height:28px; padding:0; display:flex; align-items:center; justify-content:center;">
                            <i class="ti ti-trash" style="font-size:14px;"></i>
                        </button>
                    </form>
                </div>
            @else
                <div class="profile-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            @endif
        </div>

        {{-- Form Profil --}}
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
            @csrf

            <div class="form-group">
                <label class="form-label">Foto Profil</label>
                <input type="file" name="avatar" class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control"
                       value="{{ auth()->user()->name }}">
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                       value="{{ auth()->user()->email }}">
            </div>

            <div class="text-end mt-4">
                <button class="btn btn-primary">
                    <i class="ti ti-device-floppy"></i> Simpan Profil
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
