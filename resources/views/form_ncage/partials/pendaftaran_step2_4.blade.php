@php
    $fields = [
        'produk_dihasilkan' => 'Produk Yang Dihasilkan',
        'kemampuan_produksi' => 'Kemampuan Produksi',
        'jumlah_karyawan' => 'Jumlah Karyawan',
        'kantor_cabang_1' => 'Kantor Cabang',
        'nama_jalan_1' => 'Nama Jalan',
        'kota_1' => 'Kota',
        'kode_pos_1' => 'Kode Pos',
        'perusahaan_afiliasi_2' => 'Perusahaan Afiliasi',
        'nama_jalan_2' => 'Nama Jalan',
        'kota_2' => 'Kota',
        'kode_pos_2' => 'Kode Pos',
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
           class="btn bg-white nav-text border border-2 border-active rounded-pill px-4 py-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <button type="submit" class="btn bg-active text-white rounded-pill px-4 py-2">
            Lanjutkan <i class="fa-solid fa-arrow-right"></i>
        </button>
    </div>
@endif
