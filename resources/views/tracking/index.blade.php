<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pantau Status Permohonan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/tracking.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">

</head>
<body class="bg-light">

@include('partials.navbar')

@php
    $status = $application->status_id;
@endphp

<main class="container my-5">
    <div class="status-card">
        <h1 class="main-title text-center mb-4">Pantau Status</h1>

        <h2 class="sub-title text-center mb-5">Detail Permohonan #{{ $application->created_at->format('Y') }}-{{ str_pad($application->id, 3, '0', STR_PAD_LEFT) }}</h2>
        <div class="detail-box p-4 mb-5">
            <div class="row">
                <div class="col-md-4 key">ID Permohonan</div>
                <div class="col-md-8 value">: #{{ $application->created_at->format('Y') }}-{{ str_pad($application->id, 3, '0', STR_PAD_LEFT) }}</div>
            </div>
            <div class="row">
                <div class="col-md-4 key">Tanggal Pengajuan</div>
                <div class="col-md-8 value">: {{ $application->created_at->format('j F Y') }}</div>
            </div>
            <div class="row">
                <div class="col-md-4 key">Jenis Permohonan</div>
                <div class="col-md-8 value">: {{ $application->identity->application_type ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="col-md-4 key">Jenis Permohonan NCAGE</div>
                <div class="col-md-8 value">: {{ $application->identity->ncage_request_type ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="col-md-4 key">Tujuan Penerbitan NCAGE</div>
                <div class="col-md-8 value">: {{ $application->identity->purpose ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="col-md-4 key">Tipe Entitas</div>
                <div class="col-md-8 value">: {{ $application->identity->entity_type ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="col-md-4 key">Status Saat Ini</div>
                <div class="col-md-8 value fw-bold">: {{ $application->status->name ?? 'Tidak diketahui' }}</div>
            </div>
        </div>

        <h2 class="sub-title text-center mb-4">Detail Status</h2>
        <div class="timeline-container">
            <div class="timeline-step {{ $status >= 1 ? 'completed' : 'pending' }}">
                <div class="timeline-icon"><i class="bi bi-check-lg"></i></div>
                <div class="timeline-content">
                    <div class="timeline-content-text">
                        <h5 class="fw-bold">Permohonan Dikirim</h5>
                        <p class="text-muted small mb-1">{{ $application->created_at->format('j F Y, H:i') }}</p>
                        <p class="mb-0">Berkas permohonan telah diterima oleh sistem.</p>
                    </div>
                </div>
            </div>
            @if ($status != 6)
                <div class="timeline-step
                    @if($status == 2) in-progress
                    @elseif($status == 3) revision-needed
                    @elseif($status > 3) completed
                    @else pending @endif">
                    <div class="timeline-icon">
                        @if($status > 3) <i class="bi bi-check-lg"></i>
                        @elseif($status == 3) <i class="bi bi-exclamation-circle-fill"></i>
                        @else <i class="bi bi-arrow-repeat"></i> @endif
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-content-text">
                            <h5 class="fw-bold">Verifikasi Berkas & Data</h5>
                            @if($status == 2)
                                <p class="text-primary small mb-1">Sedang Berlangsung...</p>
                                <p class="mb-0">Tim Puskod sedang melakukan verifikasi data Anda.</p>
                            @elseif($status == 3)
                                <p class="text-danger small mb-1">Permohonan Anda membutuhkan perbaikan...</p>
                                <p class="mb-0">{{ $application->revision_notes ?? 'Silakan hubungi admin.' }}</p>
                            @else
                                <p class="mb-0">Proses verifikasi berkas dan data.</p>
                            @endif
                        </div>
                        @if($status == 3)
                            <a href="{{ route('pendaftaran-ncage.show', ['step' => 1]) }}" class="btn btn-custom-dark mt-2 mt-md-0">
                                <i class="bi bi-pencil-fill me-2"></i>Lakukan Revisi Berkas
                            </a>
                        @endif
                    </div>
                </div>
            @endif
            @if ($status != 6)
                <div class="timeline-step
                    @if($status == 4) in-progress
                    @elseif($status > 4) completed
                    @else pending @endif">

                    <div class="timeline-icon">
                        @if($status > 4) <i class="bi bi-check-lg"></i> @else <i class="bi bi-hourglass-split"></i> @endif
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-content-text">
                            <h5 class="fw-bold">Proses Validasi & Unggah Sertifikat</h5>
                             @if($status == 4)
                                <p class="text-primary small mb-1">Menunggu...</p>
                            @endif
                            <p class="mb-0">Setelah verifikasi selesai, data akan diproses lebih lanjut untuk penetapan kode entitas.</p>
                        </div>
                    </div>
                </div>
            @endif
            @if ($status != 6)
                <div class="timeline-step {{ $status == 5 ? 'completed' : 'pending' }}">
                    <div class="timeline-icon">
                        @if($status == 5) <i class="bi bi-check-lg"></i> @else <i class="bi bi-file-earmark-text"></i> @endif
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-content-text">
                            <h5 class="fw-bold">Sertifikat Diterbitkan</h5>
                            @if($status == 5)
                                <p class="text-muted small mb-1">{{ $application->updated_at->format('j F Y, H:i') }}</p>
                                <p class="mb-0">Sertifikat sudah diterbitkan, silahkan unduh sertifikat pada tombol berikut ini.</p>
                            @else
                                <p class="mb-0">Sertifikat NCAGE akan diterbitkan setelah semua proses validasi selesai.</p>
                            @endif
                        </div>
                        @if($status == 5)
                            <a href="{{ route('certificate.download.record', $application->ncageRecord) }}" class="btn btn-custom-dark mt-2 mt-md-0">
                                <i class="bi bi-download me-2"></i>Unduh Sertifikat
                            </a>
                        @endif
                    </div>
                </div>
            @endif
            @if ($status == 6)
                <div class="timeline-step revision-needed">
                    <div class="timeline-icon"><i class="bi bi-x-circle-fill"></i></div>
                    <div class="timeline-content">
                        <div class="timeline-content-text">
                            <h5 class="fw-bold">Permohonan Ditolak</h5>
                            <p class="text-muted small mb-1">{{ $application->updated_at->format('j F Y, H:i') }}</p>
                            <p class="mb-0">{{ $application->revision_notes ?? 'Permohonan ditolak karena tidak memenuhi persyaratan.' }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>

</body>
</html>
