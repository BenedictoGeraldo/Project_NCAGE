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
        @auth
        <button type="button" class="hero-button hero-button-outline" id="checkEntityBtn">
            Cek Status NCAGE Perusahaan
        </button>
        @endauth
    </div>

    <div class="hero-visual-container">
        <div class="hero-ellipse"></div>
        <img src="{{ asset('images/gedung-antasari.png') }}" alt="Gedung Puskod Baranahan Kemhan" class="hero-building-image">
    </div>
</section>

<div class="modal fade" id="entityCheckModal" tabindex="-1" aria-labelledby="entityCheckModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="entityCheckModalLabel">Hasil Pengecekan Entitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="entityCheckModalBody">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@auth
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkButton = document.getElementById('checkEntityBtn');
        const modalElement = document.getElementById('entityCheckModal');
        const modal = new bootstrap.Modal(modalElement);
        const modalBody = document.getElementById('entityCheckModalBody');
        const companyName = "{{ strtoupper(Auth::user()->company_name) }}";

        checkButton.addEventListener('click', function () {
            modalBody.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Mengecek data...</p></div>';
            modal.show();

            fetch('/check-entity', {
                method: 'GET',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            })
            .then(response => response.json())
            .then(data => {
                let html = '';
                if (data.status === 'found') {
                    let statusBadge = '';
                    if (data.data.ncagesd === 'A') {
                        statusBadge = '<span class="badge bg-success">Aktif</span>';
                    } else if (data.data.ncagesd === 'H') {
                        statusBadge = '<span class="badge bg-danger">Tidak Aktif/Invalid</span>';
                    } else {
                        statusBadge = '<span class="badge bg-secondary">Status Tidak Diketahui</span>';
                    }

                    html = `
                        <div class="alert alert-success"><i class="bi bi-check-circle-fill me-2"></i>Perusahaan Ditemukan!</div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between"><strong>Nama Perusahaan:</strong> <span>${data.data.entity_name}</span></li>
                            <li class="list-group-item d-flex justify-content-between"><strong>Kode NCAGE:</strong> <span>${data.data.ncage_code}</span></li>
                            <li class="list-group-item d-flex justify-content-between"><strong>Status:</strong> ${statusBadge}</li>
                        </ul>
                    `;
                } else {
                    html = `<div class="alert alert-warning"><i class="bi bi-exclamation-triangle-fill me-2"></i>Perusahaan Anda dengan nama "${companyName}" belum terdaftar dalam sistem NCAGE.</div>`;
                }
                modalBody.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                modalBody.innerHTML = '<div class="alert alert-danger">Terjadi kesalahan saat melakukan pengecekan. Silakan coba lagi nanti.</div>';
            });
        });
    });
</script>
@endauth
@endsection
