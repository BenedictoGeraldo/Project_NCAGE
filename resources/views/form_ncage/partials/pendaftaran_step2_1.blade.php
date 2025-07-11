{{-- Step 2.1 --}}
<div class="mb-4">
    <label>Tanggal Pengajuan</label>
    @if(!empty($disabled) && $disabled)
        <div class="form-control bg-light">{{ $data['tanggal_pengajuan'] ?? '-' }}</div>
    @else
        <input type="date" name="tanggal_pengajuan" class="form-control"
               value="{{ old('tanggal_pengajuan', $data['tanggal_pengajuan'] ?? '') }}">
        @error('tanggal_pengajuan') <small class="text-danger"></small> @enderror
    @endif
</div>

<div class="custom-margin-1 d-flex gap-4">
    <label>Jenis Permohonan <span class="text-danger">*</span></label>
    @if(!empty($disabled) && $disabled)
        <div class="form-control bg-light">
            {{ $data['jenis_permohonan'] == 1 ? 'Perorangan' : ($data['jenis_permohonan'] == 2 ? 'Perusahaan / Kelompok' : '-') }}
        </div>
    @else
        <div class="d-flex gap-4">
            <div class="input-permohonan">
                <input type="radio" name="jenis_permohonan" value="1"
                       {{ old('jenis_permohonan', $data['jenis_permohonan'] ?? '') == 1 ? 'checked' : '' }}
                       class="form-check-input" id="permohonan1">
                <label class="form-check-label text-gray ms-2" for="permohonan1">Perorangan</label>
            </div>
            <div class="input-permohonan-2">
                <input type="radio" name="jenis_permohonan" value="2"
                       {{ old('jenis_permohonan', $data['jenis_permohonan'] ?? '') == 2 ? 'checked' : '' }}
                       class="form-check-input" id="permohonan2">
                <label class="form-check-label text-gray ms-2" for="permohonan2">Perusahaan / Kelompok</label>
            </div>
        </div>
    @endif
</div>

<div class="custom-margin-1 d-flex gap-4">
    <label>Jenis Permohonan Ncage <span class="text-danger">*</span></label>
    @if(!empty($disabled) && $disabled)
        <div class="form-control bg-light">
            {{ $data['jenis_permohonan_ncage'] == 1 ? 'Permohonan Baru' : ($data['jenis_permohonan_ncage'] == 2 ? 'Perbarui Data / Update' : '-') }}
        </div>
    @else
        <div class="d-flex gap-4">
            <div class="input-permohonan">
                <input type="radio" name="jenis_permohonan_ncage" value="1"
                       {{ old('jenis_permohonan_ncage', $data['jenis_permohonan_ncage'] ?? '') == 1 ? 'checked' : '' }}
                       class="form-check-input" id="permohonanncage1">
                <label class="form-check-label text-gray ms-2" for="permohonanncage1">Permohonan Baru</label>
            </div>
            <div class="input-permohonan-2">
                <input type="radio" name="jenis_permohonan_ncage" value="2"
                       {{ old('jenis_permohonan_ncage', $data['jenis_permohonan_ncage'] ?? '') == 2 ? 'checked' : '' }}
                       class="form-check-input" id="permohonanncage2">
                <label class="form-check-label text-gray ms-2" for="permohonanncage2">Perbarui Data / Update</label>
            </div>
        </div>
    @endif
</div>

@php
    $fields = [
        'tujuan_penerbitan' => 'Tujuan Penerbitan NCAGE',
        'tipe_entitas' => 'Tipe Entitas',
        'status_kepemilikan' => 'Status Kepemilikan Bangunan',
    ];
@endphp

@foreach($fields as $field => $label)
    <div class="mb-4">
        <label for="{{ $field }}">{{ $label }}{{ in_array($field, ['tujuan_penerbitan', 'tipe_entitas', 'status_kepemilikan', 'koordinat_kantor', 'npwp', 'bidang_usaha']) ? ' *' : '' }}</label>
        @if(!empty($disabled) && $disabled)
            <div class="form-control bg-light">{{ $data[$field] ?? '-' }}</div>
        @else
            <input type="text" name="{{ $field }}" class="form-control"
                   value="{{ old($field, $data[$field] ?? '') }}">
            @error($field) <small class="text-danger"></small> @enderror
        @endif
    </div>
@endforeach

<div class="custom-margin-1 d-flex gap-4">
    <label>Terdaftar (AHU.Online) <span class="text-danger">*</span></label>
    @if(!empty($disabled) && $disabled)
        <div class="form-control bg-light">
            {{ $data['terdaftar_ahu'] == 1 ? 'Terdaftar' : ($data['terdaftar_ahu'] == 2 ? 'Belum Terdaftar' : '-') }}
        </div>
    @else
        <div class="d-flex gap-4">
            <div class="input-terdaftar">
                <input type="radio" name="terdaftar_ahu" value="1"
                       {{ old('terdaftar_ahu', $data['terdaftar_ahu'] ?? '') == 1 ? 'checked' : '' }}
                       class="form-check-input" id="terdaftar1">
                <label class="form-check-label text-gray ms-2" for="terdaftar1">Terdaftar</label>
            </div>
            <div class="input-terdaftar-2">
                <input type="radio" name="terdaftar_ahu" value="2"
                       {{ old('terdaftar_ahu', $data['terdaftar_ahu'] ?? '') == 2 ? 'checked' : '' }}
                       class="form-check-input" id="terdaftar2">
                <label class="form-check-label text-gray ms-2" for="terdaftar2">Belum Terdaftar</label>
            </div>
        </div>
    @endif
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
        <label for="{{ $field }}">{{ $label }}{{ in_array($field, ['tujuan_penerbitan', 'tipe_entitas', 'status_kepemilikan', 'koordinat_kantor', 'npwp', 'bidang_usaha']) ? ' *' : '' }}</label>
        @if(!empty($disabled) && $disabled)
            <div class="form-control bg-light">{{ $data[$field] ?? '-' }}</div>
        @else
            <input type="text" name="{{ $field }}" class="form-control"
                   value="{{ old($field, $data[$field] ?? '') }}">
            @error($field) <small class="text-danger"></small> @enderror
        @endif
    </div>
@endforeach

{{-- Tombol hanya muncul jika tidak dalam mode disabled --}}
@if(empty($disabled) || !$disabled)
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('pendaftaran-ncage.show', ['step' => 1]) }}"
           class="btn bg-white nav-text border border-2 border-active rounded-pill px-4 py-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <button type="submit" class="btn bg-active text-white rounded-pill px-4 py-2">
            Lanjutkan <i class="fa-solid fa-arrow-right"></i>
        </button>
    </div>
@endif
