@extends('layouts.filament-standalone')


@section('content')
    <div class="container py-4">
        <!-- Header -->
        <div class="text-center mb-4 card p-2">
            <h3 class="fw-bold">Verifikasi Berkas Permohonan # - PT</h3>
            <div class="border border-2 border-dark-red w-100 rounded-pill"></div>
        </div>

        <div class="row d">
            <!-- Kolom Kiri -->
            <div class="col-md-6 mb-3">
                <div class="card p-4 shadow-sm h-100">
                    <!-- Tabs scrollable -->
                <div class="mb-4 overflow-auto tab-scroll" style="white-space: nowrap;">
                    <div class="d-inline-flex gap-2 mb-2">
                        <button class="btn btn-outline-dark-red rounded-pill px-4 py-2 fw-semibold active">
                            A. Identifikasi Entitas
                        </button>
                        <button class="btn btn-outline-dark-red rounded-pill px-4 py-2 fw-semibold">
                            B. Contact Person
                        </button>
                        <button class="btn btn-outline-dark-red rounded-pill px-4 py-2 fw-semibold">
                            C. Detail Badan Usaha
                        </button>
                        <button class="btn btn-outline-dark-red rounded-pill px-4 py-2 fw-semibold">
                            D. Informasi lainnya
                        </button>
                    </div>
                </div>

                    <h6 class="fw-bold mb-3">A. Identifikasi Entitas</h6>

                    <table style="line-height: 1.8; height: 400px;">
                        <tr><td>Tanggal Pengajuan</td><td class="px-2">:</td><td>{{ $applicationIdentity->submission_date ?? '-' }}</td></tr>
                        <tr><td>Jenis Permohonan</td><td class="px-2">:</td><td>{{ $applicationIdentity->application_type ?? '-' }}</td></tr>
                        <tr><td>Jenis Permohonan NCAGE</td><td class="px-2">:</td><td>{{ $applicationIdentity->ncage_request_type ?? '-' }}</td></tr>
                        <tr><td>Tujuan Penerbitan NCAGE</td><td class="px-2">:</td><td>{{ $applicationIdentity->purpose ?? '-' }}</td></tr>
                        <tr><td>Tipe Entitas</td><td class="px-2">:</td><td>{{ $applicationIdentity->entity_type ?? '-' }}</td></tr>
                        <tr><td>Status Kepemilikan Bangunan</td><td class="px-2">:</td><td>{{ $applicationIdentity->building_ownership_status ?? '-' }}</td></tr>
                        <tr><td>Terdafar (AHU.Online)</td><td class="px-2">:</td><td>{{ $applicationIdentity->is_ahu_registered ?? '-' }}</td></tr>
                        <tr><td>Koordinat Kantor (GPS Map)</td><td class="px-2">:</td><td>{{ $applicationIdentity->office_coordinate ?? '-' }}</td></tr>
                        <tr><td>NIB</td><td class="px-2">:</td><td>{{ $applicationIdentity->nib ?? '-' }}</td></tr>
                        <tr><td>NPWP</td><td class="px-2">:</td><td>{{ $applicationIdentity->npwp ?? '-' }}</td></tr>
                        <tr><td>Bidang Usaha</td><td class="px-2">:</td><td>{{ $applicationIdentity->business_field ?? '-' }}</td></tr>
                    </table>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-6 mb-3">
                <div class="card px-4 pt-4 pb-2 shadow-sm h-100">
                    <h6 class="fw-bold mb-3">Dokumen Permohonan</h6>
                    <select id="documentSelect" class="form-select mb-3" onchange="showDocumentPreview()">
                        <option value="">Pilih Dokumen</option>
                        @foreach ($documents as $name => $path)
                            <option value="{{ asset($path) }}">{{ ucfirst(str_replace('_', ' ', $name)) }}</option>
                        @endforeach
                    </select>

                    <div id="documentPreview" style="display: none;">
                        <iframe id="previewFrame" src="" width="100%" height="400px" class="mb-3 rounded border"></iframe>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="card p-2 rounded-pill">
            <div class="d-flex justify-content-between">
            <a href="" class="btn btn-outline-dark-red rounded-pill px-4 fw-semibold">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

            <div class="d-flex gap-2">
                <form method="POST" action="">
                    @csrf
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-semibold">
                        Tolak Permohonan <i class="bi bi-x-circle ms-1"></i>
                    </button>
                </form>

                <form method="POST" action="">
                    @csrf
                    <button type="submit" class="btn btn-warning rounded-pill px-4 fw-semibold">
                        Minta Revisi <i class="bi bi-pencil-square ms-1"></i>
                    </button>
                </form>

                <form method="POST" action="">
                    @csrf
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-semibold">
                        Setujui Verifikasi <i class="bi bi-check2-circle ms-1"></i>
                    </button>
                </form>
            </div>
        </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function showDocumentPreview() {
            const select = document.getElementById('documentSelect');
            const preview = document.getElementById('documentPreview');
            const frame = document.getElementById('previewFrame');

            const fileUrl = select.value;
            if (fileUrl) {
                frame.src = fileUrl;
                preview.style.display = 'block';
            } else {
                frame.src = '';
                preview.style.display = 'none';
            }
        }
    </script>
@endsection