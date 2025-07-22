@extends('layouts.main')
@section('title', 'Form NCAGE')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
@endsection

@section('content')
<div class="container mt-5">
    <div class="card rounded-4 p-4">
        <div class="card-title pt-5 px-5 text-center justify-content-center d-flex flex-column align-items-center">
            @if($step == 1)
                <h2 class="fw-bold">Unggah Dokumen Persyaratan</h2>
            @elseif($step == 2)
                <h2 class="fw-bold">Lengkapi Formulir Permintaan</h2>       
            @elseif($step == 3 && !$revisi)
                <h2 class="fw-bold">Konfirmasi & Kirim Permohonan</h2>         
            @elseif($step == 3 && $revisi)
                <h2 class="fw-bold">Revisi Data</h2>
            @endif
            
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
                        <li class="fst-italic">Template untuk Surat Permohonan NCAGE  dapat diunduh melalui <a href="{{ route('surat-permohonan.download') }}">tautan ini</a> dan Surat Pernyataan Kebenaran Data dapat diunduh melalui <a href="{{ route('surat-pernyataan.download')}}">tautan ini</a>.</li>
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
                    @if(!$revisi)                        
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
                            <a href="{{ route('pendaftaran-ncage.show', ['step' => 2, 'substep' => 4]) }}" class="btn btn-outline-dark-red nav-text border-2 border-active rounded-pill px-4 py-2"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
                            <button id="btnOpenModal" type="button" class="btn btn-dark-red text-white rounded-pill px-4 py-2">Lanjutkan <i class="fa-solid fa-arrow-right"></i></button>
                        </div>
                    @elseif($revisi)
                        @include('form_ncage.partials.pendaftaran_step1')
                        <div class="mt-5 mb-5 text-center">
                            <h4 class="fw-bold">Ringkasan Data</h4>
                        </div>
                        
                        <div class="mb-5 text-center">
                            <h4 class="fw-bold">A. Identifikasi Entitas</h4>
                        </div>

                        @include('form_ncage.partials.pendaftaran_step2_1')

                        <div class="mt-5 mb-5 text-center">
                            <h4 class="fw-bold">B. Contact Person (Narahubung)</h4>
                        </div>
                        
                        @include('form_ncage.partials.pendaftaran_step2_2')

                        <div class="mt-5 mb-5 text-center">
                            <h4 class="fw-bold">C. Detail Badan Usaha (Badan Usaha)</h4>
                        </div>

                        @include('form_ncage.partials.pendaftaran_step2_3')

                        <div class="mt-5 mb-5 text-center">
                            <h4 class="fw-bold">D. Informasi Lainnya</h4>
                        </div>

                        @include('form_ncage.partials.pendaftaran_step2_4')

                        <input type="hidden" name="revisi" value="1">
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('pendaftaran-ncage.show', ['step' => 2, 'substep' => 4]) }}" class="btn btn-outline-dark-red nav-text border-2 border-active rounded-pill px-4 py-2"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
                            <button id="btnOpenModal" type="button" class="btn btn-dark-red text-white rounded-pill px-4 py-2">Lanjutkan <i class="fa-solid fa-arrow-right"></i></button>
                        </div>
                    @endif
                @endif

                <!-- Modal Konfirmasi Submit -->
                <div class="modal fade" id="konfirmasiSubmitModal" tabindex="-1" aria-labelledby="konfirmasiSubmitModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content p-4 rounded-4 border-0">
                            <div class="text-center">
                                <h4 class="fw-bold mb-3">Apakah anda yakin?</h4>
                                <hr class="border-2 border-dark-red opacity-100 mb-4" />
                                <div class="d-flex justify-content-center mb-4">
                                    <img src="{{ asset('images/icons/icon-yakin-pop-up.svg') }}" alt="Ikon Konfirmasi" style="width: 25%; height: auto;">
                                </div>
                                <p class="mb-4">
                                    Anda akan mengirimkan data dan dokumen permohonan NCAGE Anda.
                                    Pastikan semua informasi sudah benar.
                                </p>

                                <div class="form-check text-start mb-4 d-flex align-items-start">
                                    <input class="form-check-input mt-1" type="checkbox" value="" id="pernyataanCheckbox">
                                    <label class="form-check-label ms-2 text-muted" for="pernyataanCheckbox" style="font-size: 0.6rem;">
                                        Saya menyatakan bahwa semua data yang saya masukkan dan dokumen yang saya unggah adalah benar dan dapat dipertanggungjawabkan.
                                    </label>
                                </div>

                                <div class="d-flex justify-content-between mt-3 px-2">
                                    <button type="button" class="btn btn-outline-dark-red rounded-pill px-4 fw-bold" data-bs-dismiss="modal">
                                        <i class="fa-solid fa-arrow-left"></i> Kembali
                                    </button>
                                    <button type="button" class="btn btn-dark-red rounded-pill px-4 fw-bold d-flex align-items-center" id="btnSubmitFinal" disabled>
                                        Kirim <i class="fa-solid fa-paper-plane ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Sukses -->
                <div class="modal fade" id="modalSuksesSubmit" tabindex="-1" aria-labelledby="modalSuksesSubmitLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content p-4 rounded-4 border-0 text-center">
                            <h4 class="fw-bold mb-3">Permohonan Berhasil Dikirim!</h4>
                            <hr class="border-2 border-success opacity-100 mb-4" />
                            <div class="d-flex justify-content-center mb-4">
                                <img src="{{ asset('images/icons/icon-success-dark-red.png') }}" alt="Sukses" style="width: 15%; height: auto;">
                            </div>
                            <p class="mb-4">
                                Permohonan Anda telah berhasil dikirim dan akan segera diverifikasi oleh tim kami. Anda dapat memantau statusnya di halaman "Pantau Status".
                            </p>
                            <div class="d-flex justify-content-between mt-3 gap-2">
                                <a href="{{ route('home') }}" class="btn btn-outline-dark-red rounded-pill px-4">
                                    <i class="fa-solid fa-arrow-left"></i> Kembali Ke Beranda
                                </a>
                                <a href="{{ route('tracking.index') }}" class="btn btn-dark-red rounded-pill px-4">
                                    Ke Pantau Status <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Keluar -->
                <div class="modal fade" id="exitModal" tabindex="-1" aria-labelledby="exitModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content p-4 rounded-4 shadow-sm border-0 text-center">

                            <h5 class="fw-bold fs-4 mb-2" id="exitModalLabel">Anda yakin keluar dari halaman ini?</h5>
                            <div class="border-top border-3 w-100 mx-auto mb-3"></div>

                            <!-- Ikon -->
                            <div class="my-3">
                                <img src="{{ asset('images/icons/icon-keluar-halaman.png') }}" alt="Icon Keluar" style="height: 80px;">
                            </div>

                            <p class="text-muted mb-4">Data yang sudah diunggah akan hilang.</p>

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-dark-red border-2 rounded-pill px-4 py-2 fw-semibold"
                                        data-bs-dismiss="modal">
                                    <i class="fa-solid fa-arrow-left me-2"></i> Kembali
                                </button>
                                <a href="#" class="btn btn-dark-red text-white rounded-pill px-4 py-2 fw-semibold d-flex align-items-center gap-2"
                                id="confirm-exit-btn">
                                    Ya <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>



            </form>
        </div>
    </div>
</div>
@endsection

<!--Javascript dimasukan ke dalam push -->
@push('scripts')
<script>
    // Letakkan definisi fungsi helper di sini agar bisa diakses oleh semua bagian
    function showError(field, message) {
        let errorEl = document.getElementById('error-' + field);
        if (!errorEl) {
            errorEl = document.createElement('small');
            errorEl.id = 'error-' + field;
            errorEl.className = 'text-danger d-block mt-1';
            const inputEl = document.getElementById('input-' + field);
            if(inputEl) inputEl.parentNode.appendChild(errorEl);
        }
        errorEl.textContent = message;
    }

    function clearError(field) {
        const errorEl = document.getElementById('error-' + field);
        if (errorEl) errorEl.remove();
    }
    
    function resetFileInput(field) {
        const input = document.getElementById('input-' + field);
        if(!input) return;
        input.value = '';
        document.getElementById('icon-' + field).innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i>';
        document.getElementById('desc-' + field).textContent = 'Unggah Berkas';
        document.getElementById('note-' + field).textContent = 'Maksimal file kapasitas 5 mb';
        document.getElementById('unggah-' + field).style.display = 'inline-block';
        const actionsDiv = document.getElementById('actions-' + field);
        if (actionsDiv) actionsDiv.remove();
    }

    function removeFile(field, e = null) {
        if (e) e.preventDefault();
        resetFileInput(field); // Panggil reset untuk UI
        fetch("/pendaftaran-ncage/remove-file", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ field: field })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                console.log(`File '${field}' berhasil dihapus.`);
            }
        })
        .catch(err => console.error("Error:", err));
    }

    function toggleOtherInput(select) {
        if(select.id === 'tujuan_penerbitan') {
            const otherInput = document.getElementById('tujuan_penerbitan_lainnya');
            if(select.value === '3') {
                otherInput.style.display = 'block';
            } else {
                otherInput.style.display = 'none';
                otherInput.value = '';
            }
        }
    }

    function updateSelectColor(sel) {
        if(sel.value === '') {
            sel.classList.add('placeholder');
        } else {
            sel.classList.remove('placeholder');
        }
    }

    // ===================================================
    // SATU EVENT LISTENER UTAMA
    // ===================================================
    document.addEventListener("DOMContentLoaded", function () {
        
        // BAGIAN 1: LOGIKA UPLOAD FILE
        const fields = [
            'surat_permohonan', 'surat_kebenaran', 'foto_kantor', 'sk_domisili', 
            'akta_notaris', 'sk_kemenkumham', 'siup_nib', 'company_profile', 
            'NPWP', 'surat_kuasa', 'sam_gov'
        ];

        fields.forEach(field => {
            const input = document.getElementById('input-' + field);
            if (input) {
                input.addEventListener('change', function () {
                    const file = this.files[0];
                    const formData = new FormData();
                    formData.append("file", file);
                    formData.append("field", field);

                    fetch("/pendaftaran-ncage/upload-temp", {
                        method: "POST",
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                        body: formData
                    })
                    .then(res => res.ok ? res.json() : res.json().then(err => Promise.reject(err)))
                    .then(data => {
                        if (data.success) {
                            document.getElementById('icon-' + field).innerHTML = '<i class="fa-solid fa-file-pdf text-danger"></i>';
                            document.getElementById('desc-' + field).textContent = file.name;
                            document.getElementById('note-' + field).textContent = '';
                            document.getElementById('unggah-' + field).style.display = 'none';

                            if (!document.getElementById('actions-' + field)) {
                                const actionsDiv = document.createElement('div');
                                actionsDiv.className = 'mt-2 d-flex gap-2 justify-content-center';
                                actionsDiv.id = 'actions-' + field;
                                const btnHapus = document.createElement('button');
                                btnHapus.type = 'button';
                                btnHapus.className = 'btn btn-sm btn-outline-danger rounded-pill px-3 py-2 fw-bold action-button';
                                btnHapus.innerText = 'Hapus';
                                btnHapus.onclick = (e) => removeFile(field, e);
                                actionsDiv.appendChild(btnHapus);
                                document.getElementById('desc-' + field).parentNode.appendChild(actionsDiv);
                            }
                            clearError(field);
                        }
                    })
                    .catch(err => {
                        showError(field, err.message || 'Upload gagal, cek file Anda.');
                        resetFileInput(field);
                    });
                });
            }
        });

        // BAGIAN 2: LOGIKA POP-UP SUBMIT & EXIT
        const btnOpenModal = document.getElementById('btnOpenModal');
        const btnSubmitFinal = document.getElementById('btnSubmitFinal');
        const form = document.getElementById('form-ncage');
        const checkbox = document.getElementById('pernyataanCheckbox');

        if (btnOpenModal && btnSubmitFinal) {
            btnOpenModal.addEventListener('click', function () {
                const modal = new bootstrap.Modal(document.getElementById('konfirmasiSubmitModal'));
                modal.show();
                checkbox.checked = false;
                btnSubmitFinal.disabled = true;
            });
            checkbox.addEventListener('change', function () {
                btnSubmitFinal.disabled = !this.checked;
            });
            btnSubmitFinal.addEventListener('click', function () {
                if (checkbox.checked) {
                    form.submit();
                }
            });
        }

        // --- Logika Pop-up Keluar ---
        // ... (Logika pop-up keluar Anda, tidak perlu diubah)

        // BAGIAN 3: LOGIKA MODAL SUKSES
        @if(session('submit_success'))
            const suksesModal = new bootstrap.Modal(document.getElementById('modalSuksesSubmit'));
            suksesModal.show();
        @endif

        // BAGIAN 4: LOGIKA LAINNYA (SELECT OPTIONS)
        document.querySelectorAll('select').forEach(function(sel) {
            updateSelectColor(sel);
            sel.addEventListener('change', function() {
                updateSelectColor(sel);
            });
        });
    });
</script>
@endpush