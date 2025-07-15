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
        <label for="{{ $field }}">
            {{ $label }}
            @if($field !== 'jabatan')
                <span class="text-danger" data-bs-toggle="tooltip" title="Wajib diisi">*</span>
            @endif
        </label>
        @if(!empty($disabled) && $disabled)
            <div class="form-control bg-light">{{ $data[$field] ?? '-' }}</div>
        @else
            <input
                type="{{ $field === 'email' ? 'email' : ($field === 'no_identitas' ? 'text' : 'text') }}"
                name="{{ $field }}"
                class="form-control"
                value="{{ old($field, $data[$field] ?? '') }}"
                placeholder="Masukkan {{ $label }}"
                @if($field === 'no_identitas') inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')" @endif
            >
            @error($field) <small class="text-danger">{{ $message }}</small> @enderror
        @endif
    </div>
@endforeach

{{-- Tombol hanya muncul jika tidak dalam mode disabled --}}
@if(empty($disabled) || !$disabled)
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('pendaftaran-ncage.show', ['step' => 2]) }}"
           class="btn btn-outline-dark-red nav-text border-2 border-active rounded-pill px-4 py-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <button type="submit" class="btn btn-dark-red text-white rounded-pill px-4 py-2">
            Lanjutkan <i class="fa-solid fa-arrow-right"></i>
        </button>
    </div>
@endif
