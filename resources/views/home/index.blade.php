@extends('layouts.main')

@section('title', 'Beranda')


@section('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection

@section('content')
<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">
            Portal Resmi <br> Pelayanan NCAGE Indonesia
        </h1>
        <p class="hero-subtitle">
            Daftarkan perusahaan Anda untuk mendapatkan Kode NATO Commercial and Government Entity (NCAGE) secara resmi, cepat, dan transparan.
        </p>
        <a href="#" class="hero-button">
            Daftarkan Perusahaan Anda Sekarang
        </a>
    </div>

    <div class="hero-visual-container">
        <div class="hero-ellipse"></div>
        <img src="{{ asset('images/gedung-antasari.png') }}" alt="Gedung Puskod Baranahan Kemhan" class="hero-building-image">
    </div>
</section>
@endsection