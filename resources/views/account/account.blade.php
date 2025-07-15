@extends('layouts.main')

@section('title', 'Akun')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/account.css') }}"
@endsection

@section('content')
<div class="container my-5">
    <div class="card-container-akun mx-auto">
        
        <h2 class="text-center fw-bold">Akun</h2>
        <hr class="title-divider">

        <div class="text-center my-4">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=8C1515&color=fff&rounded=true&size=96" 
                 alt="Foto Profil" class="profile-picture">
            <button class="btn btn-sm btn-outline-secondary mt-2">Ganti Foto Profil</button>
        </div>

        {{-- Kita akan membuat rute 'profile.update' nanti --}}
        <form action="{{ route('profile.update') }}" method="POST"> 
            @csrf
            @method('PATCH') {{-- Gunakan method PATCH untuk proses update --}}

            {{-- Nama Lengkap --}}
            <div class="mb-3">
                <label for="name" class="form-label fw-bold">Nama Lengkap (Point of Contact)</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}">
            </div>

            {{-- Nama Perusahaan --}}
            <div class="mb-3">
                <label for="company_name" class="form-label fw-bold">Nama Perusahaan</label>
                <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name', $user->company_name) }}">
            </div>

            {{-- Nomor Telepon --}}
            <div class="mb-3">
                <label for="phone_number" class="form-label fw-bold">Nomor Telepon (Whatsapp)</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" readonly disabled>
                <small class="form-text text-muted">Email tidak dapat diubah.</small>
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label for="password" class="form-label fw-bold">Password Baru</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah">
            </div>
             <div class="mb-4">
                <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Password Baru</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
            </div>

            {{-- Tombol Aksi --}}
            <div class="d-flex justify-content-between">
                <a href="javascript:history.back()" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Kembali
                </a>
                <button type="submit" class="btn btn-simpan">
                    Simpan<i class="fa-solid fa-floppy-disk ms-2"></i>
                </button>
            </div>

        </form>
    </div>
</div>
@endsection