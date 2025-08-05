@extends('layouts.main')

@section('title', 'Beranda')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    {{-- Meta CSRF Token sudah ada di main.blade.php, jadi tidak perlu di sini --}}
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
        @if ($hasPendingNcage)
            <a href="#" class="hero-button" data-bs-toggle="modal" data-bs-target="#sudahDaftarModal">
                Daftarkan Perusahaan Anda Sekarang
            </a>
        @elseif($hasActiveNcage)
            <a href="#" class="hero-button" data-bs-toggle="modal" data-bs-target="#activeNcageModal">
                Daftarkan Perusahaan Anda Sekarang
            </a>
        @else
            <a href="{{ route('pendaftaran-ncage.show', ['step' => 1]) }}" class="hero-button">
                Daftarkan Perusahaan Anda Sekarang
            </a>
        @endif
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

<section id="panduan" class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Panduan Penggunaan Portal NCAGE</h2>

        {{-- Navigasi Tab --}}
        <ul class="nav nav-tabs nav-justified" id="panduanTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="registrasi-tab" data-bs-toggle="tab" data-bs-target="#registrasi" type="button" role="tab" aria-controls="registrasi" aria-selected="true">1. Registrasi Akun</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="permohonan-tab" data-bs-toggle="tab" data-bs-target="#permohonan" type="button" role="tab" aria-controls="permohonan" aria-selected="false">2. Ajukan Permohonan</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pantau-tab" data-bs-toggle="tab" data-bs-target="#pantau" type="button" role="tab" aria-controls="pantau" aria-selected="false">3. Pantau Status</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="unduh-tab" data-bs-toggle="tab" data-bs-target="#unduh" type="button" role="tab" aria-controls="unduh" aria-selected="false">4. Unduh Sertifikat</button>
            </li>
        </ul>

        {{-- Konten Tab --}}
        <div class="tab-content pt-5" id="panduanTabContent">
            {{-- Konten Tab 1: Registrasi Akun --}}
            <div class="tab-pane fade show active" id="registrasi" role="tabpanel" aria-labelledby="registrasi-tab">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <img src="{{ asset('images/panduan1.png') }}" alt="Registrasi Akun" class="img-fluid rounded">
                    </div>
                    <div class="col-md-6">
                        <h3 class="fw-bold">Buat Akun Resmi Anda</h3>
                        <p class="lead">Langkah pertama adalah membuat akun untuk perusahaan Anda. Klik tombol 'Daftar' di pojok kanan atas dan isi data Point of Contact (PIC) serta informasi dasar perusahaan. Pastikan email yang Anda daftarkan aktif untuk menerima notifikasi penting dari kami.</p>
                    </div>
                </div>
            </div>
            {{-- Konten Tab 2: Ajukan Permohonan --}}
            <div class="tab-pane fade" id="permohonan" role="tabpanel" aria-labelledby="permohonan-tab">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <img src="{{ asset('images/panduan2.png') }}" alt="Ajukan Permohonan" class="img-fluid rounded">
                    </div>
                    <div class="col-md-6">
                        <h3 class="fw-bold">Lengkapi Berkas dan Isi Formulir</h3>
                        <p class="lead">Setelah login, masuk ke menu 'Pendaftaran NCAGE'. Prosesnya terdiri dari 3 langkah mudah: Unggah Berkas legalitas, Isi Formulir permintaan secara online, dan Konfirmasi & Kirim. Template dokumen yang diperlukan juga tersedia untuk diunduh di halaman tersebut.</p>
                    </div>
                </div>
            </div>
            {{-- Konten Tab 3: Pantau Status --}}
            <div class="tab-pane fade" id="pantau" role="tabpanel" aria-labelledby="pantau-tab">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <img src="{{ asset('images/panduan3.png') }}" alt="Pantau Status" class="img-fluid rounded">
                    </div>
                    <div class="col-md-6">
                        <h3 class="fw-bold">Lacak Progres Permohonan Anda</h3>
                        <p class="lead">Anda tidak akan bertanya-tanya lagi. Gunakan menu 'Pantau Status' untuk melihat progres permohonan Anda secara real-time melalui alur waktu vertikal. Jika ada perbaikan yang diperlukan, Anda akan menerima notifikasi dan bisa langsung mengunggah berkas ulang dari halaman ini.</p>
                    </div>
                </div>
            </div>
            {{-- Konten Tab 4: Unduh Sertifikat --}}
            <div class="tab-pane fade" id="unduh" role="tabpanel" aria-labelledby="unduh-tab">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <img src="{{ asset('images/panduan4.png') }}" alt="Unduh Sertifikat" class="img-fluid rounded">
                    </div>
                    <div class="col-md-6">
                        <h3 class="fw-bold">Terima Sertifikat Resmi Anda</h3>
                        <p class="lead">Setelah permohonan Anda disetujui, Anda akan menerima notifikasi bahwa sertifikat telah terbit. Kemudian Anda akan diminta untuk mengisi kuesioner serta langsung mengunduh Sertifikat NCAGE resmi langsung dari halaman 'Pantau Status'. Pastikan Anda menyimpannya dengan baik.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="faq" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Pertanyaan yang Sering Diajukan (FAQ)</h2>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="accordion" id="faqAccordion">
                    {{-- Pertanyaan 1 --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                Apa itu Kode NCAGE?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                NCAGE (NATO Commercial and Government Entity) adalah kode identifikasi unik lima digit yang diberikan kepada entitas komersial dan pemerintah untuk menstandarkan identifikasi pemasok dalam sistem logistik pertahanan global.
                            </div>
                        </div>
                    </div>
                    {{-- Pertanyaan 2 --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Siapa saja yang wajib memiliki NCAGE?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Setiap perusahaan atau entitas yang ingin berpartisipasi dalam kontrak pengadaan dengan negara anggota NATO, termasuk pemerintah Amerika Serikat, atau ingin mendaftarkan produknya untuk mendapatkan NATO Stock Number (NSN).
                            </div>
                        </div>
                    </div>
                    {{-- Pertanyaan 3 --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Apakah ada biaya untuk pendaftaran NCAGE?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Tidak ada. Seluruh proses pelayanan penerbitan Kode NCAGE melalui Pusat Kodifikasi Kementerian Pertahanan RI <b>GRATIS</b> dan tidak dipungut biaya apapun.
                            </div>
                        </div>
                    </div>
                    {{-- Pertanyaan 4 --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Dokumen apa saja yang harus saya siapkan?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Anda perlu menyiapkan scan dokumen legalitas perusahaan seperti Surat Permohonan, Akta Notaris, SK Kemenkumham, SIUP/NIB, NPWP Perusahaan, dan dokumen pendukung lainnya. Daftar lengkap tersedia di halaman pendaftaran.
                            </div>
                        </div>
                    </div>

                    {{-- Pertanyaan 5 --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                Bagaimana bila saya ingin memperpanjang status NCAGE?
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Anda dapat menggunakan menu 'Cek Entitas' di portal ini untuk melihat nama perusahaan Anda. Selanjutnya bila belum, anda perlu membuat akun baru di portal ini menggunakan nama perusahaan yang sama seperti yang ada pada menu 'Cek Entitas'.
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Modal dipindahkan ke dalam section content agar rapi --}}
<div class="modal fade" id="entityCheckModal" tabindex="-1" aria-labelledby="entityCheckModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="entityCheckModalLabel">Hasil Pengecekan Entitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="entityCheckModalBody">
                {{-- Konten diisi oleh JavaScript --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Pindahkan script khusus halaman ke dalam @push('scripts') --}}
@push('scripts')
@auth
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkButton = document.getElementById('checkEntityBtn');
        const modalElement = document.getElementById('entityCheckModal');
        const modal = new bootstrap.Modal(modalElement);
        const modalBody = document.getElementById('entityCheckModalBody');

        if (checkButton) {
            checkButton.addEventListener('click', function () {
                modalBody.innerHTML = `
                    <div class="text-center">
                        <div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>
                        <p class="mt-2">Mengecek data...</p>
                    </div>`;
                modal.show();

                fetch('/check-status', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        let html = '';

                        if (data.status === 'found') {
                            const entity = data.data;
                            const companyName = entity.entity_name;

                            const getStatusBadge = (status) => {
                                switch (status) {
                                    case 'A': return '<span class="badge bg-success">Aktif</span>';
                                    case 'H': return '<span class="badge bg-danger">Tidak Aktif/Invalid</span>';
                                    default: return '<span class="badge bg-secondary">Status Tidak Diketahui</span>';
                                }
                            };

                            const getDownloadButtons = () => {
                                if (entity.application_id && !entity.is_survey_filled) {
                                    const pantauUrl = `/pantau-status/${entity.application_id}`;
                                    return `
                                        <div class="d-grid mt-3 text-center">
                                            <p class="text-danger mb-0">Anda Belum Mengisi Survey. Silahkan ke halaman pantau status permohonan agar anda dapat mengunduh sertifikat.</p>
                                        </div>
                                        <div class="d-grid mt-3 text-center">
                                            <a href="${pantauUrl}" class="hero-button" target="_blank"><i class="bi bi-check-circle-fill me-2"></i>Pantau Status</a>
                                        </div>`;
                                }

                                let buttons = '';
                                if (entity.ncagesd === 'A') {
                                    const indoUrl = `/sertifikat/record/${entity.id}/unduh`;
                                    buttons += `
                                        <div class="d-grid mt-3 text-center">
                                            <a href="${indoUrl}" class="hero-button" target="_blank"><i class="bi bi-download me-2"></i>Unduh Sertifikat Indonesia</a>
                                        </div>`;
                                }
                                if (entity.international_certificate_path) {
                                    const intlUrl = `/sertifikat/international/${entity.application_id}/unduh`;
                                    buttons += `
                                        <div class="d-grid mt-3 text-center">
                                            <a href="${intlUrl}" class="hero-button" target="_blank"><i class="bi bi-download me-2"></i>Unduh Sertifikat Internasional</a>
                                        </div>`;
                                }
                                return buttons;
                            };

                            html = `
                                <div class="alert alert-success"><i class="bi bi-check-circle-fill me-2"></i>Perusahaan Ditemukan!</div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between"><strong>Nama Perusahaan:</strong> <span>${entity.entity_name}</span></li>
                                    <li class="list-group-item d-flex justify-content-between"><strong>Kode NCAGE:</strong> <span>${entity.ncage_code}</span></li>
                                    <li class="list-group-item d-flex justify-content-between"><strong>Status:</strong> ${getStatusBadge(entity.ncagesd)}</li>
                                    <li class="list-group-item d-flex justify-content-between"><strong>Berlaku Hingga:</strong> <span>${entity.valid_until}</span></li>
                                </ul>
                                ${getDownloadButtons()}
                            `;
                        } else {
                            const companyName = "{{ strtoupper(Auth::user()->company_name) }}";
                            html = `
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Perusahaan Anda dengan nama "${companyName}" belum terdaftar dalam sistem NCAGE.
                                </div>`;
                        }

                        modalBody.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        modalBody.innerHTML = '<div class="alert alert-danger">Terjadi kesalahan saat melakukan pengecekan. Silakan coba lagi nanti.</div>';
                    });
            });
        }
    });
</script>
@endauth
@endpush
