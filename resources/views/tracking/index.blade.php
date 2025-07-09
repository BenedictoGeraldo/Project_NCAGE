<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pantau Status Permohonan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/tracking.css') }}">
</head>
<body class="bg-light">

@php
    // Definisikan ID status untuk kemudahan pembacaan kode
    $status_ids = [
        'dikirim' => 1,
        'verifikasi' => 2,
        'perbaikan' => 3,
        'validasi' => 4,
        'terbit' => 5,
    ];
    $current_status_id = $application->status_id;
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
            <div class="timeline-step completed">
                <div class="timeline-icon"><i class="bi bi-check-lg"></i></div>
                <div class="timeline-content">
                    <div class="timeline-content-text">
                        <h5 class="fw-bold">Permohonan Dikirim</h5>
                        <p class="text-muted small mb-1">{{ $application->created_at->format('j F Y, H:i') }}</p>
                        <p class="mb-0">Berkas permohonan telah diterima oleh sistem.</p>
                    </div>
                </div>
            </div>

            @php
                $verifikasiClass = 'pending';
                if ($current_status_id == $status_ids['verifikasi']) $verifikasiClass = 'in-progress';
                if ($current_status_id == $status_ids['perbaikan']) $verifikasiClass = 'revision-needed';
                if ($current_status_id > $status_ids['perbaikan']) $verifikasiClass = 'completed';
            @endphp
            <div class="timeline-step {{ $verifikasiClass }}">
                <div class="timeline-icon">
                    @if($verifikasiClass == 'completed') <i class="bi bi-check-lg"></i>
                    @elseif($verifikasiClass == 'revision-needed') <i class="bi bi-exclamation-circle-fill"></i>
                    @else <i class="bi bi-arrow-repeat"></i> @endif
                </div>
                <div class="timeline-content">
                    <div class="timeline-content-text">
                        <h5 class="fw-bold">Verifikasi Berkas & Data</h5>
                        @if($verifikasiClass == 'in-progress')
                            <p class="text-primary small mb-1">Sedang Berlangsung...</p>
                            <p class="mb-0">Tim Puskod sedang melakukan verifikasi terhadap kelengkapan dan kesesuaian data yang Anda kirimkan.</p>
                        @elseif($verifikasiClass == 'revision-needed')
                            <p class="text-danger small mb-1">Permohonan Anda membutuhkan perbaikan...</p>
                            <p class="mb-0">{{ $application->revision_notes }}</p>
                        @else
                            <p class="mb-0">Proses verifikasi berkas dan data.</p>
                        @endif
                    </div>
                    @if($verifikasiClass == 'revision-needed')
                        <a href="#" class="btn btn-custom-dark mt-2 mt-md-0"><i class="bi bi-cloud-upload-fill me-2"></i>Unggah Berkas Ulang</a>
                    @endif
                </div>
            </div>

            @php
                $validasiClass = 'pending';
                if ($current_status_id == $status_ids['validasi']) $validasiClass = 'in-progress';
                if ($current_status_id > $status_ids['validasi']) $validasiClass = 'completed';
            @endphp
            <div class="timeline-step {{ $validasiClass }}">
                <div class="timeline-icon">
                    @if($validasiClass == 'completed') <i class="bi bi-check-lg"></i> @else <i class="bi bi-hourglass-split"></i> @endif
                </div>
                <div class="timeline-content">
                    <div class="timeline-content-text">
                        <h5 class="fw-bold">Proses Validasi</h5>
                        <p class="mb-0">Setelah verifikasi selesai, data akan diproses lebih lanjut untuk penetapan kode entitas.</p>
                    </div>
                </div>
            </div>

            @php
                $terbitClass = 'pending';
                if ($current_status_id == $status_ids['terbit']) $terbitClass = 'completed';
            @endphp
            <div class="timeline-step {{ $terbitClass }}">
                 <div class="timeline-icon">
                    @if($terbitClass == 'completed') <i class="bi bi-check-lg"></i> @else <i class="bi bi-file-earmark-text"></i> @endif
                </div>
                <div class="timeline-content">
                    <div class="timeline-content-text">
                        <h5 class="fw-bold">Sertifikat Diterbitkan</h5>
                         @if($terbitClass == 'completed')
                            <p class="text-muted small mb-1">{{ $application->updated_at->format('j F Y, H:i') }}</p>
                            <p class="mb-0">Sertifikat NCAGE telah diterbitkan, silahkan unduh sertifikat tertera.</p>
                        @else
                            <p class="mb-0">Sertifikat NCAGE akan diterbitkan setelah semua proses validasi selesai.</p>
                        @endif
                    </div>
                     @if($terbitClass == 'completed')
                        <div class="d-flex flex-column flex-sm-row gap-2 mt-2 mt-md-0">
                            <a href="{{ $application->domestic_certificate_path ? asset('upload/' . $application->domestic_certificate_path) : '#' }}"
                            class="btn btn-custom-dark {{ !$application->domestic_certificate_path ? 'disabled' : '' }}"
                            @if(!$application->domestic_certificate_path) aria-disabled="true" @endif
                            download>
                                <i class="bi bi-download me-2"></i>Unduh Sertifikat (ID)
                            </a>

                            <a href="{{ $application->international_certificate_path ? asset('upload/' . $application->international_certificate_path) : '#' }}"
                            class="btn btn-custom-dark {{ !$application->international_certificate_path ? 'disabled' : '' }}"
                            @if(!$application->international_certificate_path) aria-disabled="true" @endif
                            download>
                                <i class="bi bi-globe me-2"></i>Unduh Sertifikat (INTL)
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>

</body>
</html>
