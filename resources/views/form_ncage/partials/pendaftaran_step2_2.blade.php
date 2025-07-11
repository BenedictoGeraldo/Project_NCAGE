@php
    $fields = [
        'nama_pemohon' => 'Nama Pemohon',
        'no_identitas' => 'Nomor Identitas (KTP/SIM)',
        'alamat' => 'Alamat',
        'no_tel' => 'No. Telepon / HP (Pemohon)',
        'email' => 'E-mail (Pemohon)',
        'jabatan' => 'Jabatan',
    ];
@endphp

@foreach($fields as $field => $label)
    <div class="mb-4">
        <label for="{{ $field }}">{{ $label }} <span class="text-danger">*</span></label>
        @if(!empty($disabled) && $disabled)
            <div class="form-control bg-light">{{ $data[$field] ?? '-' }}</div>
        @else
            <input type="{{ $field === 'email' ? 'email' : 'text' }}" name="{{ $field }}" class="form-control"
                   value="{{ old($field, $data[$field] ?? '') }}">
            @error($field) <small class="text-danger"></small> @enderror
        @endif
    </div>
@endforeach

{{-- Tombol hanya muncul jika tidak dalam mode disabled --}}
@if(empty($disabled) || !$disabled)
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('pendaftaran-ncage.show', ['step' => 2]) }}"
           class="btn bg-white nav-text border border-2 border-active rounded-pill px-4 py-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <button type="submit" class="btn bg-active text-white rounded-pill px-4 py-2">
            Lanjutkan <i class="fa-solid fa-arrow-right"></i>
        </button>
    </div>
@endif
