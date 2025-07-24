@php
    $fields = [
        'provinsi' => 'Provinsi',
        'kota' => 'Kota',
        'jalan_1' => 'Jalan 1',
        'jalan_2' => 'Jalan 2',
        'kode_pos' => 'Kode Pos',
        'po_box' => 'PO.Box',
        'no_telp' => 'No.Telp. (Kantor)',
        'no_fax' => 'No. Fax. (Kantor)',
        'email_kantor' => 'E-Mail (Kantor)',
        'website_kantor' => 'Website (Kantor)',
        'perusahaan_afiliasi' => 'Perusahaan Afiliasi',
    ];

    $opsional = ['jalan_2', 'po_box', 'website_kantor', 'perusahaan_afiliasi'];
@endphp

{{-- Nama Badan Usaha --}}
<div class="mb-4">
    <label for="nama_badan_usaha">Nama Badan Usaha <span class="text-danger" data-bs-toggle="tooltip" title="Wajib diisi">*</span></label>
    @if(!empty($disabled) && $disabled)
        <div class="form-control bg-light">{{ $data['nama_badan_usaha'] ?? '-' }}</div>
    @else
        <input type="text" name="nama_badan_usaha" class="form-control" placeholder="Masukkan Nama Badan Usaha"
               value="{{ old('nama_badan_usaha', $data['nama_badan_usaha'] ?? '') }}" readonly>
        @error('nama_badan_usaha') <small class="text-danger">{{ $message }}</small> @enderror
    @endif
</div>

{{-- Input Fields --}}
@foreach($fields as $field => $label)
    <div class="mb-4">
        <label for="{{ $field }}">
            {{ $label }}
            @unless(in_array($field, $opsional))
                <span class="text-danger" data-bs-toggle="tooltip" title="Wajib diisi">*</span>
            @endunless
        </label>

        @if(!empty($disabled) && $disabled)
            <div class="form-control bg-light">
                {!! nl2br(e($data[$field] ?? '-')) !!}
            </div>
        @else
            <input type="{{ $field === 'email_kantor' ? 'email' : 'text' }}" name="{{ $field }}" class="form-control" placeholder="Masukkan {{ $label }}"
                    value="{{ old($field, $data[$field] ?? '') }}"
                    @if($field === 'no_telp' || $field === 'no_fax' || $field === 'kode_pos')
                        inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                    @endif>
            @error($field) <small class="text-danger">{{ $message }}</small> @enderror
        @endif
    </div>
@endforeach

{{-- Tombol Navigasi --}}
@if(empty($disabled) && !session('is_revision'))
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('pendaftaran-ncage.show', ['step' => 2, 'substep' => 2]) }}"
           class="btn btn-outline-dark-red nav-text border-2 border-active rounded-pill px-4 py-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <button type="submit" class="btn btn-dark-red text-white rounded-pill px-4 py-2">
            Lanjutkan <i class="fa-solid fa-arrow-right"></i>
        </button>
    </div>
@endif
