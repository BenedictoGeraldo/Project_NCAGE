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
                    <hr class="title-divider line w-100 rounded-pill">
                </div>
            </div>

            {{-- Section Pembina Proyek --}}
            <div class="row justify-content-center mb-5">
                <div class="col-12 text-center mb-4">
                    <h2 class="team-section-title">Pembina Proyek</h2>
                    <p class="text-muted">Pusat Kodifikasi Baranahan Kemhan</p>
                </div>

                {{-- Desain baru untuk menampilkan nama saja dalam kolom --}}
                <div class="col-lg-8">
                    <div class="puskod-names-container">
                        <div class="puskod-name-item">Nama 1</div>
                        <div class="puskod-name-item">Nama 2</div>
                        <div class="puskod-name-item">Nama 3</div>
                        <div class="puskod-name-item">Nama 4</div>
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
                        <img src="{{ asset('images/abdul_hakim.jpg') }}" class="team-card-img mb-3" alt="Foto Profil Abdul Hakim Darasalam">
                        <div class="team-card-body">
                            <p class="team-card-major">SI Sistem Informasi</p>
                            <p class="team-card-name">Abdul Hakim Darasalam</p>
                            <p class="team-card-role">Developer</p>
                            <div class="team-card-social">
                                <a href="#" target="_blank" class="social-icon"><i class="fab fa-linkedin"></i></a>
                                <a href="https://github.com/abdulhakimdarasalam" target="_blank" class="social-icon"><i class="fab fa-github"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="team-card">
                        <img src="{{ asset('images/benedicto_geraldo.jpg') }}" class="team-card-img mb-3" alt="Foto Profil Benedicto Geraldo Doa Dawa">
                        <div class="team-card-body">
                            <p class="team-card-major">SI Sistem Informasi</p>
                            <p class="team-card-name">Benedicto Geraldo Doa Dawa</p>
                            <p class="team-card-role">Developer</p>
                            <div class="team-card-social">
                                <a href="#" target="_blank" class="social-icon"><i class="fab fa-linkedin"></i></a>
                                <a href="https://github.com/BenedictoGeraldo" target="_blank" class="social-icon"><i class="fab fa-github"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="team-card">
                        <img src="{{ asset('images/evan_fulka.jpg') }}" class="team-card-img mb-3" alt="Foto Profil Evan Fulka Bima Maheswara">
                        <div class="team-card-body">
                            <p class="team-card-major">SI Sistem Informasi</p>
                            <p class="team-card-name">Evan Fulka Bima Maheswara</p>
                            <p class="team-card-role">Developer</p>
                            <div class="team-card-social">
                                <a href="#" target="_blank" class="social-icon"><i class="fab fa-linkedin"></i></a>
                                <a href="https://github.com/evanfulka" target="_blank" class="social-icon"><i class="fab fa-github"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="team-card">
                        <img src="{{ asset('images/m_raihan_ramadhani.png') }}" class="team-card-img mb-3" alt="Foto Profil M. Raihan Ramadhani">
                        <div class="team-card-body">
                            <p class="team-card-major">SI Sistem Informasi</p>
                            <p class="team-card-name">M. Raihan Ramadhani</p>
                            <p class="team-card-role">UI/UX Designer</p>
                            <div class="team-card-social">
                                <a href="https://www.linkedin.com/in/m-raihan-ramadhani/" target="_blank" class="social-icon"><i class="fab fa-linkedin"></i></a>
                                <a href="https://github.com/AthrunCode" target="_blank" class="social-icon"><i class="fab fa-github"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection