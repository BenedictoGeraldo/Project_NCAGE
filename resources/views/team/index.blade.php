@extends('layouts.main')

@section('title', 'Tim Pengembang')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/team.css') }}">
@endsection

@section('content')
<div class="team-page-background">
    <div class="container py-5">
        <div class="team-content-wrapper">
            {{-- Judul Halaman --}}
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h1 class="page-title">Tim Pengembang & Kolaborator</h1>
                    <hr class="title-divider">
                </div>
            </div>

            {{-- Section Tim Pusat Kodifikasi --}}
            <div class="row justify-content-center">
                <div class="col-12 text-center mb-4">
                    <h2 class="team-section-title">Tim Pusat Kodifikasi (Opskod)</h2>
                </div>
                {{-- Kartu Anggota Tim Puskod (Placeholder) --}}
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="team-card">
                        <img src="https://via.placeholder.com/150" class="team-card-img mb-3" alt="Foto Profil">
                        <div class="team-card-body">
                            <p class="team-card-name">Pak Dede</p>
                            <p class="team-card-role">Jabatan/NIP</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="team-card">
                        <img src="https://via.placeholder.com/150" class="team-card-img mb-3" alt="Foto Profil">
                        <div class="team-card-body">
                            <p class="team-card-name">Pak Cahyadi</p>
                            <p class="team-card-role">Jabatan/NIP</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="team-card">
                        <img src="https://via.placeholder.com/150" class="team-card-img mb-3" alt="Foto Profil">
                        <div class="team-card-body">
                            <p class="team-card-name">Pak Suhud</p>
                            <p class="team-card-role">Jabatan/NIP</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section Tim Magang UPNVJ --}}
            <div class="row justify-content-center mt-4">
                <div class="col-12 text-center mb-4">
                    <h2 class="team-section-title">Tim Pengembang (Mahasiswa Magang UPN Veteran Jakarta)</h2>
                </div>
                {{-- Kartu Anggota Tim Magang --}}
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="team-card">
                        <img src="https://via.placeholder.com/150" class="team-card-img mb-3" alt="Foto Profil Abdul Hakim Darasalam">
                        <div class="team-card-body">
                            <p class="team-card-major">SI Sistem Informasi</p>
                            <p class="team-card-name">Abdul Hakim Darasalam</p>
                            <p class="team-card-role">Developer</p>
                            <div class="team-card-social">
                                <a href="#" class="social-icon"><i class="fab fa-linkedin"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-github"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="team-card">
                        <img src="https://via.placeholder.com/150" class="team-card-img mb-3" alt="Foto Profil Benedicto Geraldo Doa Dawa">
                        <div class="team-card-body">
                            <p class="team-card-major">SI Sistem Informasi</p>
                            <p class="team-card-name">Benedicto Geraldo Doa Dawa</p>
                            <p class="team-card-role">Developer</p>
                            <div class="team-card-social">
                                <a href="#" class="social-icon"><i class="fab fa-linkedin"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-github"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="team-card">
                        <img src="https://via.placeholder.com/150" class="team-card-img mb-3" alt="Foto Profil Evan Fulka Bima Maheswara">
                        <div class="team-card-body">
                            <p class="team-card-major">SI Sistem Informasi</p>
                            <p class="team-card-name">Evan Fulka Bima Maheswara</p>
                            <p class="team-card-role">Developer</p>
                            <div class="team-card-social">
                                <a href="#" class="social-icon"><i class="fab fa-linkedin"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-github"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="team-card">
                        <img src="https://via.placeholder.com/150" class="team-card-img mb-3" alt="Foto Profil M. Reihan Ramadhani">
                        <div class="team-card-body">
                            <p class="team-card-major">SI Sistem Informasi</p>
                            <p class="team-card-name">M. Raihan Ramadhani</p>
                            <p class="team-card-role">UI/UX Designer</p>
                            <div class="team-card-social">
                                <a href="#" class="social-icon"><i class="fab fa-linkedin"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-github"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection