@extends('layouts.main')

@section('title', 'Pantau Status Permohonan')

@section('styles')
    {{-- CSS khusus untuk halaman ini --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/tracking.css') }}">
@endsection

@section('content')

@php
    $status = $application->status_id;
    $applicationId = $application->created_at->format('Y') . '-' . str_pad($application->id, 3, '0', STR_PAD_LEFT);
@endphp

<main class="container my-4 my-md-5">
    <div class="status-card">
        <h1 class="main-title text-center mb-4">Pantau Status</h1>

        <div class="text-center mb-5">
            <h2 class="sub-title mb-0">Detail Permohonan #{{ $applicationId }}</h2>
        </div>

        {{-- Kotak Detail Permohonan --}}
        <div class="detail-box p-3 p-md-4 mb-5">
            <dl class="row mb-0">
                <dt class="col-sm-5 col-md-4">ID Permohonan</dt>
                <dd class="col-sm-7 col-md-8">: #{{ $applicationId }}</dd>

                <dt class="col-sm-5 col-md-4">Tanggal Pengajuan</dt>
                <dd class="col-sm-7 col-md-8">: {{ $application->created_at->translatedFormat('j F Y') }}</dd>

                <dt class="col-sm-5 col-md-4">Jenis Permohonan</dt>
                <dd class="col-sm-7 col-md-8">: {{ $application->identity->application_type_label ?? '-' }}</dd>

                <dt class="col-sm-5 col-md-4">Jenis Permohonan NCAGE</dt>
                <dd class="col-sm-7 col-md-8">: {{ $application->identity->ncage_request_type_label ?? '-' }}</dd>

                <dt class="col-sm-5 col-md-4">Tujuan Penerbitan NCAGE</dt>
                <dd class="col-sm-7 col-md-8">: {{ $application->identity->purpose_label ?? '-' }}</dd>

                <dt class="col-sm-5 col-md-4">Tipe Entitas</dt>
                <dd class="col-sm-7 col-md-8">: {{ $application->identity->entity_type_label ?? '-' }}</dd>

                <dt class="col-sm-5 col-md-4">Status Saat Ini</dt>
                <dd class="col-sm-7 col-md-8 fw-bold">: {{ $application->status->name ?? 'Tidak diketahui' }}</dd>
            </dl>
        </div>

        <h2 class="sub-title text-center mb-4">Detail Status</h2>
        <div class="timeline-container">
            {{-- Status 1: Permohonan Dikirim --}}
            <div class="timeline-step {{ $status >= 1 ? 'completed' : 'pending' }}">
                <div class="timeline-icon"><i class="bi bi-check-lg"></i></div>
                <div class="timeline-content">
                    <div class="timeline-content-text">
                        <h5 class="fw-bold">Permohonan Dikirim</h5>
                        <p class="text-muted small mb-1">{{ $application->created_at->translatedFormat('j F Y, H:i') }}</p>
                        <p class="mb-0">Berkas permohonan telah diterima oleh sistem.</p>
                    </div>
                </div>
            </div>

            @if ($status != 6)
                {{-- Status 2 & 3: Verifikasi --}}
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
                            @if($status >= 2)
                                <p class="text-muted small mb-1">{{ $application->updated_at->translatedFormat('j F Y, H:i') }}</p>
                            @endif
                            @if($status == 2)
                                <p class="text-primary small mb-1">Sedang Berlangsung...</p>
                                <p class="mb-0">Tim Puskod sedang melakukan verifikasi data Anda.</p>
                            @elseif($status == 3)
                                <p class="text-danger small mb-1">Permohonan Anda membutuhkan perbaikan.</p>
                                <p class="mb-0">{{ $application->revision_notes ?? 'Silakan hubungi admin.' }}</p>
                            @else
                                <p class="mb-0">Proses verifikasi berkas dan data.</p>
                            @endif
                        </div>
                        @if($status == 3)
                            <a href="{{ route('pendaftaran-ncage.show', ['step' => 1]) }}" class="btn btn-custom-dark mt-3 mt-md-0">
                                <i class="bi bi-pencil-fill me-2"></i>Lakukan Revisi
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Status 4: Validasi & Unggah Sertifikat --}}
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
                            @if($status >= 4)
                                <p class="text-muted small mb-1">{{ $application->updated_at->translatedFormat('j F Y, H:i') }}</p>
                            @endif
                            <p class="mb-0">Setelah verifikasi selesai, data akan diproses lebih lanjut.</p>
                        </div>
                    </div>
                </div>

                {{-- Status 5: Sertifikat Diterbitkan --}}
                <div class="timeline-step {{ $status == 5 ? 'completed' : 'pending' }}">
                    <div class="timeline-icon">
                        @if($status == 5) <i class="bi bi-check-lg"></i> @else <i class="bi bi-file-earmark-text"></i> @endif
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-content-text">
                            <h5 class="fw-bold">Sertifikat Diterbitkan</h5>
                            @if($status == 5)
                                <p class="text-muted small mb-1">{{ $application->updated_at->translatedFormat('j F Y, H:i') }}</p>
                                <p class="mb-0">Sertifikat sudah diterbitkan. Silakan isi kuesioner untuk mengunduh.</p>
                            @else
                                <p class="mb-0">Sertifikat akan tersedia setelah semua proses validasi selesai.</p>
                            @endif
                        </div>

                        @if($status == 5)
                            {{-- Tombol-tombol ini akan muncul setelah survei diisi --}}
                            <div id="download-buttons" style="display: {{ $application->survey ? 'block' : 'none' }};">
                                <div class="d-flex flex-column flex-md-row mt-3">
                                    @if($application->ncageRecord)
                                        <a href="{{ route('certificate.download.record', $application->ncageRecord) }}" class="btn btn-custom-dark">
                                            <i class="bi bi-download me-2"></i>Unduh Sertifikat Indonesia
                                        </a>
                                    @endif
                                    @if($application->international_certificate_path)
                                        <a href="{{ route('certificate.download.international', $application) }}" class="btn btn-custom-dark mt-2 mt-md-0 ms-md-2">
                                            <i class="bi bi-download me-2"></i>Unduh Sertifikat Internasional
                                        </a>
                                    @endif
                                </div>
                            </div>

                            {{-- Tombol untuk membuka modal survei, hanya muncul jika survei belum diisi --}}
                            @if(!$application->survey)
                            <div id="survey-button-container" class="mt-3">
                                <button type="button" class="btn btn-custom-dark" data-bs-toggle="modal" data-bs-target="#surveyModal">
                                    <i class="bi bi-pencil-square me-2"></i> Isi Kuesioner Kepuasan
                                </button>
                            </div>
                            @endif
                        @endif
                    </div>
                </div>


                @if($status == 5 && !$application->survey)
                <div class="modal fade" id="surveyModal" tabindex="-1" aria-labelledby="surveyModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content survey-modal-content">
                            <div class="modal-header border-0">
                                <h4 class="modal-title w-100 text-center fw-bold" id="surveyModalLabel">Survey Kepuasan Pelayanan NCAGE</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-progress-bar"></div>
                            <div class="modal-body">
                                <form id="surveyForm" class="px-3">
                                    @csrf
                                    <p class="small">Catatan: <br>• (<span class="text-danger">*</span>) Menunjukkan pertanyaan yang wajib diisi</p>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">1. Bagaimana pendapat Saudara tentang kesesuaian persyaratan pelayanan dengan jenis pelayanan di Puskod: <span class="text-danger">*</span></label>
                                        <div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q1_kesesuaian_persyaratan" value="1" required><label class="form-check-label">Tidak Sesuai</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q1_kesesuaian_persyaratan" value="2"><label class="form-check-label">Kurang Sesuai</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q1_kesesuaian_persyaratan" value="3"><label class="form-check-label">Sesuai</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q1_kesesuaian_persyaratan" value="4"><label class="form-check-label">Sangat Sesuai</label></div>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">2. Bagaimana pendapat Saudara tentang kemudahan prosedur pelayanan di Puskod: <span class="text-danger">*</span></label>
                                        <div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q2_kemudahan_prosedur" value="1" required><label class="form-check-label">Tidak Mudah</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q2_kemudahan_prosedur" value="2"><label class="form-check-label">Kurang Mudah</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q2_kemudahan_prosedur" value="3"><label class="form-check-label">Mudah</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q2_kemudahan_prosedur" value="4"><label class="form-check-label">Sangat Mudah</label></div>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">3. Bagaimana pendapat Saudara tentang kecepatan Pelayanan di Puskod: <span class="text-danger">*</span></label>
                                        <div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q3_kecepatan_pelayanan" value="1" required><label class="form-check-label">Tidak Cepat</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q3_kecepatan_pelayanan" value="2"><label class="form-check-label">Kurang Cepat</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q3_kecepatan_pelayanan" value="3"><label class="form-check-label">Cepat</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q3_kecepatan_pelayanan" value="4"><label class="form-check-label">Sangat Cepat</label></div>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">4. Bagaimana pendapat Saudara tentang kewajaran biaya/tarif dalam pelayanan: <span class="text-danger">*</span></label>
                                        <div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q4_kewajaran_biaya" value="1" required><label class="form-check-label">Sangat Mahal</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q4_kewajaran_biaya" value="2"><label class="form-check-label">Mahal</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q4_kewajaran_biaya" value="3"><label class="form-check-label">Murah</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q4_kewajaran_biaya" value="4"><label class="form-check-label">Gratis</label></div>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">5. Bagaimana pendapat Saudara tentang kesesuaian produk pelayanan antara yang tercantum dalam standar pelayanan dengan hasil yang diberikan: <span class="text-danger">*</span></label>
                                        <div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q5_kesesuaian_produk" value="1" required><label class="form-check-label">Tidak Sesuai</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q5_kesesuaian_produk" value="2"><label class="form-check-label">Kurang Sesuai</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q5_kesesuaian_produk" value="3"><label class="form-check-label">Sesuai</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q5_kesesuaian_produk" value="4"><label class="form-check-label">Sangat Sesuai</label></div>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">6. Bagaimana pendapat Saudara tentang kompetensi/kemampuan petugas dalam pelayanan: <span class="text-danger">*</span></label>
                                        <div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q6_kompetensi_petugas" value="1" required><label class="form-check-label">Tidak Kompeten</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q6_kompetensi_petugas" value="2"><label class="form-check-label">Kurang Kompeten</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q6_kompetensi_petugas" value="3"><label class="form-check-label">Kompeten</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q6_kompetensi_petugas" value="4"><label class="form-check-label">Sangat Kompeten</label></div>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">7. Bagaimana pendapat Saudara tentang perilaku petugas dalam pelayanan terkait kesopanan dan keramahan: <span class="text-danger">*</span></label>
                                        <div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q7_perilaku_petugas" value="1" required><label class="form-check-label">Tidak Sopan & Ramah</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q7_perilaku_petugas" value="2"><label class="form-check-label">Kurang Sopan & Ramah</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q7_perilaku_petugas" value="3"><label class="form-check-label">Sopan & Ramah</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q7_perilaku_petugas" value="4"><label class="form-check-label">Sangat Sopan & Ramah</label></div>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">8. Bagaimana pendapat Saudara tentang kualitas sarana dan prasarana: <span class="text-danger">*</span></label>
                                        <div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q8_kualitas_sarana" value="1" required><label class="form-check-label">Buruk</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q8_kualitas_sarana" value="2"><label class="form-check-label">Cukup</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q8_kualitas_sarana" value="3"><label class="form-check-label">Baik</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q8_kualitas_sarana" value="4"><label class="form-check-label">Sangat Baik</label></div>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">9. Bagaimana pendapat Saudara tentang penanganan pengaduan pengguna layanan: <span class="text-danger">*</span></label>
                                        <div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q9_penanganan_pengaduan" value="1" required><label class="form-check-label">Tidak Ada</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q9_penanganan_pengaduan" value="2"><label class="form-check-label">Ada Tetapi Tidak Berfungsi</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q9_penanganan_pengaduan" value="3"><label class="form-check-label">Berfungsi Kurang Maksimal</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="q9_penanganan_pengaduan" value="4"><label class="form-check-label">Dikelola Dengan Baik</label></div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 p-0 pt-3">
                                        <button type="submit" class="btn btn-survey-submit w-100 fw-bold">Kirim <i class="bi bi-send-fill ms-2"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endif

            {{-- Status 6: Ditolak --}}
            @if ($status == 6)
                <div class="timeline-step revision-needed">
                    <div class="timeline-icon"><i class="bi bi-x-circle-fill"></i></div>
                    <div class="timeline-content">
                        <div class="timeline-content-text">
                            <h5 class="fw-bold">Permohonan Ditolak</h5>
                            <p class="text-muted small mb-1">{{ $application->updated_at->translatedFormat('j F Y, H:i') }}</p>
                            <p class="mb-0">{{ $application->revision_notes ?? 'Permohonan ditolak karena tidak memenuhi persyaratan.' }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
@push('scripts')
@if($status == 5 && !$application->survey)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const surveyForm = document.getElementById('surveyForm');
    const surveyModalElement = document.getElementById('surveyModal');
    const surveyModal = new bootstrap.Modal(surveyModalElement);

    surveyForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(surveyForm);
        const submitButton = surveyForm.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Mengirim...';

        fetch('{{ route("survey.store", $application) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                surveyModal.hide();

                // === BAGIAN YANG DIUBAH: GANTI ALERT() DENGAN SWEETALERT2 ===
                Swal.fire({
                    title: 'Terima Kasih!',
                    text: 'Survei Anda telah berhasil dikirim.',
                    icon: 'success',
                    customClass: {
                        // Gunakan class CSS yang sama dengan tombol Kirim untuk konsistensi warna
                        confirmButton: 'btn btn-survey-submit'
                    },
                    buttonsStyling: false // Penting agar customClass bisa diterapkan
                }).then((result) => {
                    // Setelah pengguna menekan "OK", tampilkan tombol unduh
                    if (result.isConfirmed) {
                        document.getElementById('survey-button-container').style.display = 'none';
                        document.getElementById('download-buttons').style.display = 'block';
                    }
                });

            } else {
                let errorMessages = 'Gagal mengirim survei. Pastikan semua pertanyaan wajib diisi.';
                if(data.errors){
                    for(const key in data.errors){
                        errorMessages += `\n- ${data.errors[key][0]}`;
                    }
                }
                // Tampilkan error dengan SweetAlert juga agar seragam
                Swal.fire({
                    title: 'Oops...',
                    text: errorMessages,
                    icon: 'error',
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan pada sistem. Silakan coba lagi.',
                icon: 'error',
            });
        })
        .finally(() => {
            submitButton.disabled = false;
            submitButton.innerHTML = 'Kirim <i class="bi bi-send-fill ms-2"></i>';
        });
    });
});
</script>
@endif
@endpush
