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

    <main class="container my-5">
        <div class="status-card">
            <h1 class="main-title text-center mb-4">Pantau Status</h1>
            <h2 class="sub-title text-center mb-5">Detail Permohonan #2025-001</h2>

            <div class="detail-box p-4 mb-5">
                <div class="row">
                    <div class="col-md-3 key">ID Permohonan</div>
                    <div class="col-md-9 value">: #2025-001</div>
                </div>
                <div class="row">
                    <div class="col-md-3 key">Tanggal Pengajuan</div>
                    <div class="col-md-9 value">: 6 Juli 2025</div>
                </div>
                <div class="row">
                    <div class="col-md-3 key">Jenis Permohonan</div>
                    <div class="col-md-9 value">: Perusahaan</div>
                </div>
                <div class="row">
                    <div class="col-md-3 key">Jenis Permohonan NCAGE</div>
                    <div class="col-md-9 value">: Permohonan Baru</div>
                </div>
                <div class="row">
                    <div class="col-md-3 key">Tujuan Penerbitan NCAGE</div>
                    <div class="col-md-9 value">: Pengadaan</div>
                </div>
                <div class="row">
                    <div class="col-md-3 key">Tipe Entitas</div>
                    <div class="col-md-9 value">: E</div>
                </div>
                <div class="row">
                    <div class="col-md-3 key">Status Saat Ini</div>
                    <div class="col-md-9 value">: Verifikasi Berkas & Data</div>
                </div>
            </div>

            <h2 class="sub-title text-center mb-4">Detail Status</h2>

            <div class="timeline-container">
                <div class="timeline-step completed">
                    <div class="timeline-icon">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <div class="timeline-content">
                        <h5 class="fw-bold">Permohonan Dikirim</h5>
                        <p class="text-muted small mb-1">6 Juli 2025, 15:58</p>
                        <p class="mb-0">Berkas permohonan telah diterima oleh sistem.</p>
                    </div>
                </div>

                <div class="timeline-step in-progress">
                    <div class="timeline-icon">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <div class="timeline-content">
                        <h5 class="fw-bold">Verifikasi Berkas & Data</h5>
                        <p class="text-primary small mb-1">Sedang Berlangsung...</p>
                        <p class="mb-0">Tim Puskod sedang melakukan verifikasi terhadap kelengkapan dan kesesuaian data yang Anda kirimkan.</p>
                    </div>
                </div>

                <div class="timeline-step pending">
                    <div class="timeline-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="timeline-content">
                        <h5 class="fw-bold">Proses Validasi</h5>
                        <p class="text-muted small mb-1">Menunggu</p>
                        <p class="mb-0">Setelah verifikasi selesai, data akan diproses lebih lanjut untuk penetapan kode entitas.</p>
                    </div>
                </div>

                <div class="timeline-step pending">
                    <div class="timeline-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div class="timeline-content">
                        <h5 class="fw-bold">Sertifikat Diterbitkan</h5>
                        <p class="text-muted small mb-1">Menunggu</p>
                        <p class="mb-0">Sertifikat NCAGE akan diterbitkan setelah semua proses internal selesai.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
