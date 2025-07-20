@php
    $formSession = session('form_ncage', []);
    $data['documents'] = $formSession['documents'] ?? [];

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

    $optionalFields = ['sk_domisili', 'surat_kuasa', 'sam_gov'];
@endphp


@foreach($fields as $field => $label)
    <div class="mb-3">
        <label for="{{ $field }}">
            {{ $label }}
            @if (!in_array($field, $optionalFields))
                <span class="text-danger" data-bs-toggle="tooltip" title="Wajib diisi">*</span>
            @endif
        </label>

        @if(!empty($disabled) && $disabled)
            {{-- Mode Disabled --}}
            @if(!empty($data['documents'][$field]))
                <div class="border rounded p-2 bg-light d-flex align-items-center">
                    <i class="fa-solid fa-file-pdf text-danger me-2"></i>
                    {{ basename($data['documents'][$field]) }}
                </div>
            @else
                <div class="text-muted fst-italic">Belum ada berkas</div>
            @endif
        @else
            {{-- Mode Normal --}}
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

                    <input type="file" name="{{ $field }}" id="input-{{ $field }}" hidden accept="application/pdf">

                    @if(!empty($data['documents'][$field]))
                        <div class="mt-2 d-flex gap-2 justify-content-center" id="actions-{{ $field }}">
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-2 fw-bold action-button"
                                onclick="removeFile('{{ $field }}', event)">
                                Hapus
                            </button>
                        </div>
                    @endif
                </label>
            </div>
        @endif
        @error($field)
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
@endforeach

@if(empty($disabled) && !session('is_revision'))
    <div class="d-flex justify-content-between mt-4">
        <button type="submit" name="cancel" value="1" class="btn btn-outline-dark-red nav-text border-2 border-active rounded-pill px-4 py-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </button>
        <button type="submit" class="btn btn-dark-red text-white rounded-pill px-4 py-2">
            Lanjutkan <i class="fa-solid fa-arrow-right"></i>
        </button>
    </div>
@endif