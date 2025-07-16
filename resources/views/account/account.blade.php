@extends('layouts.main')

@section('title', 'Pengaturan Akun')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">
@endsection

@section('content')
<div class="container my-5">
    <div class="card-container-akun mx-auto">
        
        <h2 class="text-center fw-bold">Akun</h2>
        <hr class="title-divider">

        {{-- Menampilkan pesan sukses setelah update --}}
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        
        <form id="profile-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            {{-- Bagian Foto Profil --}}
            <div class="profile-picture-container">
                @if (Auth::user()->profile_photo_path)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Foto Profil" class="profile-picture" id="profile-image-preview">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=8C1515&color=fff&rounded=true&size=96" 
                         alt="Foto Profil" class="profile-picture" id="profile-image-preview">
                @endif
                <button type="button" class="btn btn-change-profile" id="change-profile-button">Ganti Foto Profil</button>
                <input type="file" name="profile_photo" id="profile_photo_input" class="d-none" accept="image/*">
            </div>

            {{-- Form Fields --}}
            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap (Point of Contact)</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}">
            </div>

            <div class="mb-3">
                <label for="company_name" class="form-label">Nama Perusahaan</label>
                <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name', $user->company_name) }}">
            </div>

            <div class="mb-3">
                <label for="phone_number" class="form-label">Nomor Telepon (Whatsapp)</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" value="{{ $user->email }}" readonly disabled>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" value="••••••••" readonly disabled>
            </div>

            {{-- Tombol Aksi --}}
            <div class="d-flex justify-content-between mt-5">
                <a href="{{ route('beranda') }}" class="btn btn-kembali">
                    <i class="fa-solid fa-arrow-left me-2"></i>Kembali
                </a>
                <button type="submit" class="btn btn-simpan">
                    Simpan<i class="fa-solid fa-floppy-disk ms-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>

    <!--Alert-->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal-content">
                <div class="modal-body p-4 text-center">
                    <h4 class="fw-bold">Konfirmasi Perubahan</h4>
                    <hr class="confirm-divider">
                    <div class="my-4">
                        {{-- Ganti dengan ikon yang sesuai, misal ikon simpan --}}
                        <i class="fa-solid fa-floppy-disk confirm-icon"></i>
                    </div>
                    <p>Apakah Anda yakin ingin menyimpan perubahan?</p>
                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <button type="button" class="btn btn-modal-kembali" data-bs-dismiss="modal">
                            <i class="fa-solid fa-arrow-left me-2"></i>Kembali
                        </button>
                        <button type="button" class="btn btn-modal-simpan" id="confirm-update-btn">
                            Simpan<i class="fa-solid fa-floppy-disk ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Fungsionalitas untuk tombol "Ganti Foto Profil"
    const changeProfileButton = document.getElementById('change-profile-button');
    const profilePhotoInput = document.getElementById('profile_photo_input');
    const profileImagePreview = document.getElementById('profile-image-preview');

    if (changeProfileButton) {
        changeProfileButton.addEventListener('click', function() {
            profilePhotoInput.click();
        });
    }

    if (profilePhotoInput) {
        profilePhotoInput.addEventListener('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                profileImagePreview.src = URL.createObjectURL(file);
            }
        });
    }
    
    const profileForm = document.getElementById('profile-form');
    const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
    const confirmButton = document.getElementById('confirm-update-btn');

    if (profileForm) {
        profileForm.addEventListener('submit', function(event) {
            // Hentikan submit form dan tampilkan modal
            event.preventDefault();
            confirmationModal.show();
        });
    }

    if (confirmButton) {
        // Jika tombol "Simpan" di dalam modal diklik, submit form-nya
        confirmButton.addEventListener('click', function() {
            profileForm.submit();
        });
    }
</script>
@endpush