@extends('layouts.main')
@section('title', 'Form NCAGE')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
@endsection

@section('content')
<div class="container mt-5">
    <div class="card rounded-4 p-4">
        <div class="card-title pt-5 px-5 text-center justify-content-center d-flex flex-column align-items-center">
            <h2 class="fw-bold">Unggah Dokumen Persyaratan</h2>
            <div class="line w-100 rounded-pill"></div>
        </div>
        <div class="form-container py-4 px-5">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($step == 1)                
                <div class="notes mb-3">
                    <p class="mb-0">Catatan:</p>
                    <ul class="px-3">
                        <li class="fst-italic">(<span class="text-danger">*</span>) Wajib untuk diisi</li>
                        <li class="fst-italic">Pastikan semua dokumen yang diunggah adalah hasil scan yang jelas dan dapat dibaca.</li>
                        <li class="fst-italic">Semua file harus dalam format PDF.</li>
                        <li class="fst-italic">Ukuran maksimal per file adalah 5 MB.</li>
                        <li class="fst-italic">Khusus untuk persyaratan SAM.GOV. (Jumlah Karakter Alamat sebanyak 54)</li>
                        <li class="fst-italic">Template untuk Surat Permohonan NCAGE  dapat diunduh melalui tautan ini dan Surat Pernyataan Kebenaran Data dapat diunduh melalui tautan ini.</li>
                    </ul>
                </div>
            @elseif($step == 2)
            <div class="mb-5 text-center">
                <h4 class="fw-bold">
                        @if($substep == 1)
                        A. Identifikasi Entitas
                        @elseif($substep == 2)
                        B. Contact Person (Narahubung)
                        @elseif($substep == 3)
                        C. Detail Badan Usaha
                        @elseif($substep == 4)
                        D. Informasi Lainnya
                        @endif
                    </h4>
                </div>
            @elseif($step == 3)
            <div class="mb-5 text-center">
                <h4 class="fw-bold">Ringkasan Dokumen</h4>
            </div>
            @endif

            <form id="form-ncage" method="POST" action="{{ route('pendaftaran-ncage.handle-step') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="step" value="{{ $step }}">

                @if($step == 1)
                    @include('form_ncage.partials.pendaftaran_step1', ['disabled' => false])
                @elseif($step == 2)
                    <input type="hidden" name="substep" value="{{ $substep }}">

                    {{-- Start Step 2.1 --}}
                    @if($substep == 1)
                        @include('form_ncage.partials.pendaftaran_step2_1')
                    {{-- End Step 2.1 --}}

                    {{-- Start Step 2.2 --}}
                    @elseif($substep == 2)
                        @include('form_ncage.partials.pendaftaran_step2_2')
                    {{-- End Step 2.2 --}}

                    {{-- Start Step 2.3 --}}
                    @elseif($substep == 3)
                        @include('form_ncage.partials.pendaftaran_step2_3')
                    {{-- End Step 2.3 --}}
                    
                    {{-- Start Step 2.4 --}}
                    @elseif($substep == 4)
                        @include('form_ncage.partials.pendaftaran_step2_4')
                    {{-- End Step 2.4 --}}

                    @endif

                @elseif($step == 3)
                    @include('form_ncage.partials.pendaftaran_step1', ['disabled' => true])
                    <div class="mt-5 mb-5 text-center">
                        <h4 class="fw-bold">Ringkasan Data</h4>
                    </div>
                    
                    <div class="mb-5 text-center">
                        <h4 class="fw-bold">A. Identifikasi Entitas</h4>
                    </div>

                    @include('form_ncage.partials.pendaftaran_step2_1', ['disabled' => true])

                    <div class="mt-5 mb-5 text-center">
                        <h4 class="fw-bold">B. Contact Person (Narahubung)</h4>
                    </div>
                    
                    @include('form_ncage.partials.pendaftaran_step2_2', ['disabled' => true])

                    <div class="mt-5 mb-5 text-center">
                        <h4 class="fw-bold">C. Detail Badan Usaha (Badan Usaha)</h4>
                    </div>

                    @include('form_ncage.partials.pendaftaran_step2_3', ['disabled' => true])

                    <div class="mt-5 mb-5 text-center">
                        <h4 class="fw-bold">D. Informasi Lainnya</h4>
                    </div>

                    @include('form_ncage.partials.pendaftaran_step2_4', ['disabled' => true])
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('pendaftaran-ncage.show', ['step' => 2, 'substep' => 4]) }}" class="btn bg-white nav-text border border-2 border-active rounded-pill px-4 py-2"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
                        <button id="btnOpenModal" type="button" class="btn bg-active text-white rounded-pill px-4 py-2">Lanjutkan <i class="fa-solid fa-arrow-right"></i></button>
                    </div>
                @endif

                <!-- Modal Konfirmasi Submit -->
                <div class="modal fade" id="konfirmasiSubmitModal" tabindex="-1" aria-labelledby="konfirmasiSubmitModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-4">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold" id="konfirmasiSubmitModalLabel">Konfirmasi Pengiriman Data</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                Apakah Anda yakin ingin mengirimkan seluruh data ini? Setelah dikirim, data tidak dapat diubah.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary rounded-pill px-4 py-2" data-bs-dismiss="modal">Batal</button>
                                <button type="button" class="btn btn-primary rounded-pill px-4 py-2" id="btnSubmitFinal">Ya, Kirim Data</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const fields = [
        'surat_permohonan',
        'surat_kebenaran',
        'foto_kantor',
        'sk_domisili',
        'akta_notaris',
        'sk_kemenkumham',
        'siup_nib',
        'company_profile',
        'NPWP',
        'surat_kuasa',
        'sam_gov'
    ];

    fields.forEach(field => {
        const input = document.getElementById('input-' + field);
        if (input) {
            input.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    document.getElementById('icon-' + field).innerHTML = '<i class="fa-solid fa-file-pdf text-danger"></i>';
                    document.getElementById('desc-' + field).textContent = file.name;
                    document.getElementById('note-' + field).textContent = '';
                    document.getElementById('unggah-' + field).style.display = 'none';

                    // Cek apakah tombol aksi belum ada
                    if (!document.getElementById('actions-' + field)) {
                        const actionsDiv = document.createElement('div');
                        actionsDiv.className = 'mt-2 d-flex gap-2 justify-content-center';
                        actionsDiv.id = 'actions-' + field;

                        // Tombol Ganti
                        // const btnGanti = document.createElement('button');
                        // btnGanti.type = 'button';
                        // btnGanti.className = 'btn btn-sm btn-warning';
                        // btnGanti.innerText = 'Ganti File';
                        // btnGanti.onclick = function () {
                        //     document.getElementById('input-' + field).click();
                        // };

                        // Tombol Hapus
                        const btnHapus = document.createElement('button');
                        btnHapus.type = 'button';
                        btnHapus.className = 'btn btn-sm btn-outline-danger rounded-pill px-3 py-2 fw-bold action-button';
                        btnHapus.innerText = 'Hapus';
                        btnHapus.onclick = function (e) {
                            e.preventDefault();
                            removeFile(field, e);
                        };

                        // Masukkan tombol ke dalam div
                        // actionsDiv.appendChild(btnGanti);
                        actionsDiv.appendChild(btnHapus);

                        // Tempel setelah label
                        document.getElementById('desc-' + field).parentNode.appendChild(actionsDiv);
                    }
                }
            });
        }
    });

    function removeFile(field, e = null) {
        if (e) {
            e.preventDefault();
        }
        // Reset icon, text, note, & tombol
        document.getElementById('icon-' + field).innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i>';
        document.getElementById('desc-' + field).textContent = 'Unggah Berkas';
        document.getElementById('note-' + field).textContent = 'Maksimal file kapasitas 5 mb';
        document.getElementById('unggah-' + field).style.display = '';
        document.getElementById('input-' + field).value = '';

        // Hilangkan tombol aksi
        const actions = document.getElementById('actions-' + field);
        if (actions) {
            actions.remove();
        }

        // Tambah hidden input untuk info hapus (agar backend tahu)
        let hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'hapus_file[]';
        hidden.value = field;
        document.getElementById('form-ncage').appendChild(hidden);

        // Debugging: Pastikan input ditambahkan
        console.log('Input hapus_file[] ditambahkan untuk field:', field);
        console.log('Form sekarang:', document.querySelector('form').innerHTML);
    }

    // script pop up
    document.addEventListener("DOMContentLoaded", function() {
        const btnOpenModal = document.getElementById('btnOpenModal');
        const btnSubmitFinal = document.getElementById('btnSubmitFinal');
        const form = document.getElementById('form-ncage');

        if (btnOpenModal && btnSubmitFinal) {
            btnOpenModal.addEventListener('click', function() {
                const modal = new bootstrap.Modal(document.getElementById('konfirmasiSubmitModal'));
                modal.show();
            });

            btnSubmitFinal.addEventListener('click', function() {
                form.submit();
            });
        }
    });
</script>

@endsection
