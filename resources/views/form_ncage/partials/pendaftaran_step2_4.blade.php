@php
    $fields = [
        'produk_dihasilkan' => 'Produk Yang Dihasilkan',
        'kemampuan_produksi' => 'Kemampuan Produksi',
        'jumlah_karyawan' => 'Jumlah Karyawan',
        'kantor_cabang_1' => 'Kantor Cabang',
        'nama_jalan_1' => 'Nama Jalan Kantor Cabang',
        'kota_1' => 'Kota Kantor Cabang',
        'kode_pos_1' => 'Kode Pos Kantor Cabang',
        'perusahaan_afiliasi_2' => 'Perusahaan Afiliasi',
        'nama_jalan_2' => 'Nama Jalan Perusahaan Afiliasi',
        'kota_2' => 'Kota Perusahaan Afiliasi',
        'kode_pos_2' => 'Kode Pos Perusahaan Afiliasi',
    ];
@endphp

@foreach($fields as $field => $label)
    <div class="mb-4">
        <label for="{{ $field }}">{{ $label }}</label>
        @if(!empty($disabled) && $disabled)
            <div class="form-control bg-light">
                {{ $data[$field] ?? '-' }}
            </div>
        @else
            <input type="text" name="{{ $field }}" class="form-control"
                   placeholder="Masukkan {{ $label }}"
                   value="{{ old($field, $data[$field] ?? '') }}">
            @error($field) <small class="text-danger"></small> @enderror
        @endif
    </div>
@endforeach

@if(empty($disabled) || !$disabled)
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('pendaftaran-ncage.show', ['step' => 2, 'substep' => 3]) }}"
           class="btn btn-outline-dark-red nav-text border-2 border-active rounded-pill px-4 py-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <button type="submit" class="btn btn-dark-red text-white rounded-pill px-4 py-2">
            Lanjutkan <i class="fa-solid fa-arrow-right"></i>
        </button>
    </div>
@endif
