@php
    $fields = [
        'alamat_kantor' => 'Alamat Kantor',
        'kode_pos' => 'Kode Pos',
        'po_box' => 'PO.Box',
        'no_telp' => 'No.Telp. (Kantor)',
        'no_fax' => 'No. Fax. (Kantor)',
        'email_kantor' => 'E-Mail (Kantor)',
        'website_kantor' => 'Website (Kantor)',
        'perusahaan_afiliasi' => 'Perusahaan Afiliasi',
    ];
@endphp

<div class="mb-4">
    <label for="nama_badan_usaha">Nama Badan Usaha <span class="text-danger">*</span></label>
    @if(!empty($disabled) && $disabled)
        <div class="form-control bg-light">{{ $data['nama_badan_usaha'] ?? '-' }}</div>
    @else
        <input type="text" name="nama_badan_usaha" class="form-control" placeholder="Masukkan Nama Badan Usaha"
               value="{{ old('nama_badan_usaha', $data['nama_badan_usaha'] ?? '') }}">
        @error('nama_badan_usaha') <small class="text-danger"></small> @enderror
    @endif
</div>

{{-- Provinsi & Kota --}}
<div class="mb-4 d-flex gap-3">
    {{-- Provinsi --}}
    <div class="w-50">
        <label for="provinsi">Provinsi <span class="text-danger">*</span></label>
        @if(!empty($disabled) && $disabled)
            <div class="form-control bg-light">{{ $data['provinsi'] ?? '-' }}</div>
        @else
            <select name="provinsi" class="form-control">
                <option value="">Pilih Provinsi</option>
                <option value="DKI Jakarta" {{ old('provinsi', $data['provinsi'] ?? '') == 'DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta</option>
                <option value="Jawa Barat" {{ old('provinsi', $data['provinsi'] ?? '') == 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                {{-- Tambahkan provinsi lainnya --}}
            </select>
            @error('provinsi') <small class="text-danger">{{ $message }}</small> @enderror
        @endif
    </div>

    {{-- Kota --}}
    <div class="w-50">
        <label for="kota">Kota <span class="text-danger">*</span></label>
        @if(!empty($disabled) && $disabled)
            <div class="form-control bg-light">{{ $data['kota'] ?? '-' }}</div>
        @else
            <select name="kota" class="form-control">
                <option value="">Pilih Kota</option>
                <option value="Jakarta Selatan" {{ old('kota', $data['kota'] ?? '') == 'Jakarta Selatan' ? 'selected' : '' }}>Jakarta Selatan</option>
                <option value="Jakarta Utara" {{ old('kota', $data['kota'] ?? '') == 'Jakarta Utara' ? 'selected' : '' }}>Jakarta Utara</option>
                {{-- Tambahkan kota lainnya --}}
            </select>
            @error('kota') <small class="text-danger">{{ $message }}</small> @enderror
        @endif
    </div>
</div>


{{-- Input Lainnya --}}
@foreach($fields as $field => $label)
    <div class="mb-4">
        <label for="{{ $field }}">{{ $label }} @if(in_array($field, ['alamat_kantor', 'kode_pos', 'po_box', 'no_telp', 'email_kantor'])) <span class="text-danger">*</span> @endif</label>
        @if(!empty($disabled) && $disabled)
            <div class="form-control bg-light">
                {!! nl2br(e($data[$field] ?? '-')) !!}
            </div>
        @else
            @if($field === 'alamat_kantor')
                <textarea name="{{ $field }}" class="form-control" placeholder="Masukkan {{ $label }}">{{ old($field, $data[$field] ?? '') }}</textarea>
            @else
                <input type="{{ $field === 'email_kantor' ? 'email' : 'text' }}" name="{{ $field }}" class="form-control" placeholder="Masukkan {{ $label }}"
                       value="{{ old($field, $data[$field] ?? '') }}">
            @endif
            @error($field) <small class="text-danger"></small> @enderror
        @endif
    </div>
@endforeach

{{-- Tombol hanya muncul jika tidak dalam mode disabled --}}
@if(empty($disabled) || !$disabled)
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('pendaftaran-ncage.show', ['step' => 2, 'substep' => 2]) }}"
           class="btn bg-white nav-text border border-2 border-active rounded-pill px-4 py-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <button type="submit" class="btn bg-active text-white rounded-pill px-4 py-2">
            Lanjutkan <i class="fa-solid fa-arrow-right"></i>
        </button>
    </div>
@endif
