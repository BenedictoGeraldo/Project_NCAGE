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

            <form id="form-ncage" method="POST" action="{{ route('pendaftaran-ncage.handle-step') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="step" value="{{ $step }}">

                @if($step == 1)
                    @php
                        $fields = [
                            'surat_permohonan' => 'Surat Permohonan NCAGE',
                            'surat_kebenaran' => 'Surat Pernyataan Kebenaran Data',
                            'foto_kantor' => 'Foto Kantor',
                            'sk_domisili' => 'SK Domisili',
                            'akta_notaris' => 'Akta Notaris',
                            'sk_kemenkumham' => 'SK Kemenkumham',
                            'siup_nib' => 'SIUP/NIB (Nomor Induk Berusaha)',
                            'company_profile' => 'Company Profile Perusahaan',
                            'NPWP' => 'NPWP Perusahaan',
                            'surat_kuasa' => 'Surat Kuasa',
                            'sam_gov' => 'Daftar Isian SAM.GOV',
                        ];
                    @endphp

                    @foreach($fields as $field => $label)
                        <div class="mb-3">
                            <label for="{{ $field }}">{{ $label }}</label>
                            <div class="up-file">
                                <label class="custom-file-upload w-100">
                                    <div class="icon" id="icon-{{ $field }}">
                                        @if(!empty($data['documents'][$field]))
                                            <i class="fa-solid fa-file-pdf text-danger"></i>
                                        @else
                                            <i class="fa-solid fa-cloud-arrow-up"></i>
                                        @endif
                                    </div>

                                    <div class="desc" id="desc-{{ $field }}">
                                        @if(!empty($data['documents'][$field]))
                                            {{ basename($data['documents'][$field]) }}
                                        @else
                                            Unggah Berkas
                                        @endif
                                    </div>

                                    <div class="note" id="note-{{ $field }}">
                                        @if(empty($data['documents'][$field]))
                                            Maksimal file kapasitas 5 mb
                                        @endif
                                    </div>

                                    <span class="btn-upload" id="unggah-{{ $field }}"
                                        style="{{ !empty($data['documents'][$field]) ? 'display: none;' : '' }}">
                                        Unggah Berkas
                                    </span>

                                    <input type="file" name="{{ $field }}" id="input-{{ $field }}" hidden>

                                    <!-- Tombol Aksi jika file sudah ada -->
                                    @if(!empty($data['documents'][$field]))
                                        <div class="mt-2 d-flex gap-2 justify-content-center" id="actions-{{ $field }}">
                                            <!-- Button Hapus -->
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-2 fw-bold action-button" onclick="removeFile('{{ $field }}', event)">
                                                Hapus
                                            </button>
                                            <!-- Button Ganti -->
                                            {{-- <button type="button" class="btn btn-sm btn-warning" onclick="document.getElementById('input-{{ $field }}').click();">
                                                Ganti File
                                            </button> --}}
                                        </div>
                                    @endif
                                </label>
                            </div>
                            @error($field) <small class="text-danger"></small> @enderror
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" name="cancel" value="1" class="btn bg-white nav-text border border-2 border-active rounded-pill px-4 py-2">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </button>
                        <button type="submit" class="btn bg-active text-white rounded-pill px-4 py-2">
                            Lanjutkan <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>

                @elseif($step == 2)
                    <div class="mb-3">
                        <label>Email:</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $data['email'] ?? '') }}">
                        @error('email') <small class="text-danger"></small> @enderror
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('pendaftaran-ncage.show', ['step' => 1]) }}" class="btn bg-white nav-text border border-2 border-active rounded-pill px-4 py-2"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
                        <button type="submit" class="btn bg-active text-white rounded-pill px-4 py-2">Lanjutkan <i class="fa-solid fa-arrow-right"></i></button>
                    </div>

                {{-- @elseif($step == 3)
                    <h4>Konfirmasi Data</h4>
                    <p><strong>Nama:</strong> {{ $data['nama'] ?? '' }}</p>
                    <p><strong>Email:</strong> {{ $data['email'] ?? '' }}</p>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('form.show', ['step' => 2]) }}" class="btn btn-secondary">Previous</a>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div> --}}
                @endif
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
</script>

@endsection
