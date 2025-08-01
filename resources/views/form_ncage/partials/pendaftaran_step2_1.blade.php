{{-- Step 2.1 --}}
<div class="mb-4">
    <label>Tanggal Pengajuan</label>
    @if(!empty($disabled) && $disabled)
        <div class="form-control bg-light">{{ $data['tanggal_pengajuan'] ?? '-' }}</div>
    @else
        <input type="date" name="tanggal_pengajuan" class="form-control" 
           value="{{ old('tanggal_pengajuan', $data['tanggal_pengajuan'] ?? \Carbon\Carbon::now()->toDateString()) }}"
           readonly>
    @endif
</div>

<div class="custom-margin-1">
    <div class="d-flex gap-4">
        <label>Jenis Permohonan <span class="text-danger">*</span></label>

        <div class="d-flex gap-4">
            @php
                $selected = old('jenis_permohonan', $data['jenis_permohonan'] ?? '');
            @endphp

            <div class="input-permohonan">
                <input type="radio" name="jenis_permohonan" value="1"
                       {{ $selected == 1 ? 'checked' : '' }}
                       class="form-check-input" id="permohonan1"
                       {{ !empty($disabled) && $disabled ? 'disabled' : '' }}>
                <label class="form-check-label text-gray ms-2" for="permohonan1">Perorangan</label>
            </div>

            <div class="input-permohonan-2">
                <input type="radio" name="jenis_permohonan" value="2"
                       {{ $selected == 2 ? 'checked' : '' }}
                       class="form-check-input" id="permohonan2"
                       {{ !empty($disabled) && $disabled ? 'disabled' : '' }}>
                <label class="form-check-label text-gray ms-2" for="permohonan2">Perusahaan / Kelompok</label>
            </div>
        </div>
    </div>
    @error('jenis_permohonan') <small class="text-danger">{{ $message }}</small> @enderror
</div>

{{-- <div class="custom-margin-1">
    <div class="d-flex gap-4">
        <label>Jenis Permohonan Ncage <span class="text-danger">*</span></label>
        <div class="d-flex gap-4">
            @php
                $selected = old('jenis_permohonan_ncage', $data['jenis_permohonan_ncage'] ?? '');
            @endphp

            <div class="input-permohonan">
                <input type="radio" name="jenis_permohonan_ncage" value="1"
                       {{ $selected == 1 ? 'checked' : '' }}
                       class="form-check-input" id="permohonanncage1"
                       {{ !empty($disabled) && $disabled ? 'disabled' : '' }}>
                <label class="form-check-label text-gray ms-2" for="permohonanncage1">Permohonan Baru</label>
            </div>

            <div class="input-permohonan-2">
                <input type="radio" name="jenis_permohonan_ncage" value="2"
                       {{ $selected == 2 ? 'checked' : '' }}
                       class="form-check-input" id="permohonanncage2"
                       {{ !empty($disabled) && $disabled ? 'disabled' : '' }}>
                <label class="form-check-label text-gray ms-2" for="permohonanncage2">Perbarui Data / Update</label>
            </div>
        </div>
    </div>
    @error('jenis_permohonan_ncage') <small class="text-danger">{{ $message }}</small> @enderror
</div> --}}


@php
    $fields = [
        'tujuan_penerbitan' => 'Tujuan Penerbitan NCAGE',
        'tipe_entitas' => 'Tipe Entitas',
        'status_kepemilikan' => 'Status Kepemilikan Bangunan',
    ];

    $options = [
        'tujuan_penerbitan' => [
            '1' => 'SAM.GOV',
            '2' => 'Pengadaan',
            '3' => 'Lainnya',
        ],
        'tipe_entitas' => [
            'E' => 'E - Pabrikan',
            'F' => 'F - Supplier/Distributor/Sales/Ritel',
            'G' => 'G - Jasa Layanan/Organisasi Profesional/Konstruksi/Bank/Universitas/Konsultan/Yayasan/LSM',
            'H' => 'H - Pemerintah, Kementrian, Lembaga, U.O.Militer',
        ],
        'status_kepemilikan' => [
            '1' => 'Sendiri',
            '2' => 'Sewa',
            '3' => 'Pemerintah',
        ],
    ];
@endphp

@foreach($fields as $field => $label)
    <div class="mb-4">
        <label for="{{ $field }}">{{ $label }} <span class="text-danger" data-bs-toggle="tooltip" title="Wajib diisi">*</span></label>

        @if(!empty($disabled) && $disabled)
            @php
                $value = $data[$field] ?? null;
                $displayValue = $options[$field][$value] ?? $value ?? '-';
            @endphp
            <div class="form-control bg-light">{{ $displayValue }}</div>

        @else
            @if(in_array($field, ['tujuan_penerbitan', 'tipe_entitas', 'status_kepemilikan']))
                <select name="{{ $field }}" id="{{ $field }}" class="form-control form-select" onchange="toggleOtherInput(this)">
                    <option value="" disabled selected>-- Pilih {{ $label }} --</option>
                    @foreach($options[$field] as $key => $val)
                        <option value="{{ $key }}" {{ old($field, $data[$field] ?? '') == $key ? 'selected' : '' }}>
                            {{ $val }}
                        </option>
                    @endforeach
                </select>

                @if($field == 'tujuan_penerbitan')
                    <input type="text" name="tujuan_penerbitan_lainnya" id="tujuan_penerbitan_lainnya" class="form-control mt-2"
                        placeholder="Isi tujuan penerbitan lainnya..."
                        value="{{ old('tujuan_penerbitan_lainnya', $data['tujuan_penerbitan_lainnya'] ?? '') }}"
                        style="display: {{ old('tujuan_penerbitan', $data['tujuan_penerbitan'] ?? '') == '3' ? 'block' : 'none' }};">

                    @error('tujuan_penerbitan_lainnya')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                @endif
            @else
                <input type="text" name="{{ $field }}" class="form-control"
                       value="{{ old($field, $data[$field] ?? '') }}">
            @endif

            @error($field) <small class="text-danger">{{ $message }}</small> @enderror
        @endif
    </div>
@endforeach

<div class="custom-margin-1">
    <div class="d-flex gap-4">
        <label>Terdaftar (AHU.Online)
            <span class="text-danger" data-bs-toggle="tooltip" title="Wajib diisi">*</span>
        </label>

        <div class="d-flex gap-4">
            @php
                $selected = old('terdaftar_ahu', $data['terdaftar_ahu'] ?? '');
            @endphp

            <div class="input-terdaftar">
                <input type="radio" name="terdaftar_ahu" value="1"
                       {{ $selected == 1 ? 'checked' : '' }}
                       class="form-check-input" id="terdaftar1"
                       {{ !empty($disabled) && $disabled ? 'disabled' : '' }}>
                <label class="form-check-label text-gray ms-2" for="terdaftar1">Terdaftar</label>
            </div>

            <div class="input-terdaftar-2">
                <input type="radio" name="terdaftar_ahu" value="2"
                       {{ $selected == 2 ? 'checked' : '' }}
                       class="form-check-input" id="terdaftar2"
                       {{ !empty($disabled) && $disabled ? 'disabled' : '' }}>
                <label class="form-check-label text-gray ms-2" for="terdaftar2">Belum Terdaftar</label>
            </div>
        </div>
    </div>

    {{-- Hidden field to submit the selected value if radio is disabled --}}
    @if(!empty($disabled) && $disabled)
        <input type="hidden" name="terdaftar_ahu" value="{{ $selected }}">
    @endif

    @error('terdaftar_ahu') <small class="text-danger">{{ $message }}</small> @enderror
</div>

@php
    $fields = [
        'koordinat_kantor' => 'Koordinat Kantor (GPS Map)',
        'nib' => 'NIB (Nomor Induk Perusahaan)',
        'npwp' => 'NPWP (Nomor Pokok Wajib Pajak)',
        'bidang_usaha' => 'Bidang Usaha',
    ];
@endphp

@foreach($fields as $field => $label)
    <div class="mb-4">
        <label for="{{ $field }}">
            {{ $label }}
            @if (in_array($field, ['tujuan_penerbitan', 'tipe_entitas', 'status_kepemilikan', 'koordinat_kantor', 'npwp', 'nib', 'bidang_usaha']))
                <span class="text-danger" data-bs-toggle="tooltip" title="Wajib diisi">*</span>
            @endif
        </label>
        @if(!empty($disabled) && $disabled)
            <div class="form-control bg-light">{{ $data[$field] ?? '-' }}</div>
        @else
            <input type="text" name="{{ $field }}" class="form-control"
                   value="{{ old($field, $data[$field] ?? '') }}" placeholder="Masukkan {{ $label }}">
            @error($field) <small class="text-danger">{{ $message }}</small> @enderror
        @endif
    </div>
@endforeach

{{-- Tombol hanya muncul jika tidak dalam mode disabled --}}
@if(empty($disabled) && !session('is_revision'))
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('pendaftaran-ncage.show', ['step' => 1]) }}"
           class="btn btn-outline-dark-red nav-text border-2 border-active rounded-pill px-4 py-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <button type="submit" class="btn btn-dark-red text-white rounded-pill px-4 py-2">
            Lanjutkan <i class="fa-solid fa-arrow-right"></i>
        </button>
    </div>
@endif
