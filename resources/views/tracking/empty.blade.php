@extends('layouts.main')

@section('title', 'Pantau Status')

@section('styles')
    <style>
        .empty-state-card {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            max-width: 900px;
            margin: auto;
            text-align: center;
        }
        .empty-state-title {
            font-weight: 600;
            color: #333;
            padding-bottom: 1rem;
            border-bottom: 2px solid #D1D5DB;
            margin-bottom: 3rem;
            font-size: 1.75rem;
        }
        .empty-state-image {
            max-width: 300px;
            margin-bottom: 1.5rem;
        }
        .empty-state-text {
            color: #6B7280;
            margin-bottom: 2rem;
        }
        .empty-state-button {
            background-color: #8C1515;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.2s;
            display: inline-block;
        }
        .empty-state-button:hover {
            background-color: #701111;
            color: white;
        }

        /* --- Gaya Responsif untuk Mobile --- */
        @media (max-width: 767.98px) {
            .empty-state-card {
                padding: 24px;
            }
            .empty-state-title {
                font-size: 1.3rem;
                margin-bottom: 2rem;
            }
            .empty-state-image {
                max-width: 220px;
            }
            .empty-state-text {
                font-size: 1rem;
            }
            .empty-state-button {
                width: 100%;
                padding: 14px;
            }
        }
    </style>
@endsection

@section('content')
<main class="container my-5">
    <div class="empty-state-card">
        <h1 class="empty-state-title">Pantau Status Pendaftaran</h1>

        <img src="{{ asset('images/undraw_page-not-found_6wni 1.png') }}" alt="Ilustrasi Belum Mendaftar" class="empty-state-image">

        @if($hasPendingNcage)
        <a href="#" class="empty-state-button" data-bs-toggle="modal" data-bs-target="#sudahDaftarModal">
                Daftarkan Perusahaan Anda Sekarang
            </a>
        @elseif($hasActiveNcage)
        <p class="empty-state-text fs-5">Anda Sudah Memiliki Kode NCAGE</p>

        <a href="#" class="empty-state-button" data-bs-toggle="modal" data-bs-target="#activeNcageModal">
                Perbarui Status Kode NCAGE Anda Sekarang
            </a>
        @else
        <p class="empty-state-text fs-5">Anda belum mendaftarkan perusahaan Anda</p>

        <a href="{{ route('pendaftaran-ncage.show', ['step' => 1]) }}" class="empty-state-button">
            Daftarkan Perusahaan Anda Sekarang
        </a>
        @endif
    </div>
</main>
@endsection
