@extends('layouts.filament-standalone')


@section('content')
    <div class="container py-4">
        <!-- Header -->
        <div class="text-center mb-4 card p-2">
            <h3 class="fw-bold">Verifikasi Berkas Permohonan # - PT</h3>
            <div class="border border-2 border-dark-red w-100 rounded-pill"></div>
        </div>

        <div class="row d">
            <!-- Kolom Kiri -->
            <div class="col-md-6 mb-3">
                <div class="card p-4 shadow-sm h-100">
                    <!-- Tabs scrollable -->
                <div class="mb-4 overflow-auto tab-scroll" style="white-space: nowrap;">
                    <div class="d-inline-flex gap-2 mb-2">
                        <button type="button" class="btn btn-outline-dark-red rounded-pill px-4 py-2 fw-semibold bg-active text-white"
                            data-tab="identity" onclick="toggleTabSection(this)">
                            A. Identifikasi Entitas
                        </button>
                        <button type="button" class="btn btn-outline-dark-red rounded-pill px-4 py-2 fw-semibold"
                            data-tab="contact" onclick="toggleTabSection(this)">
                            B. Contact Person
                        </button>
                        <button type="button" class="btn btn-outline-dark-red rounded-pill px-4 py-2 fw-semibold"
                            data-tab="company" onclick="toggleTabSection(this)">
                            C. Detail Badan Usaha
                        </button>
                        <button type="button" class="btn btn-outline-dark-red rounded-pill px-4 py-2 fw-semibold"
                            data-tab="other" onclick="toggleTabSection(this)">
                            D. Informasi lainnya
                        </button>
                    </div>
                </div>

                    @php
                        $identityFields = [
                            'Tanggal Pengajuan' => $applicationIdentity->submission_date ?? '-',
                            'Jenis Permohonan' => $applicationIdentity->application_type ?? '-',
                            'Jenis Permohonan NCAGE' => $applicationIdentity->ncage_request_type ?? '-',
                            'Tujuan Penerbitan NCAGE' => $applicationIdentity->purpose ?? '-',
                            'Tipe Entitas' => $applicationIdentity->entity_type ?? '-',
                            'Status Kepemilikan Bangunan' => $applicationIdentity->building_ownership_status ?? '-',
                            'Terdafar (AHU.Online)' => $applicationIdentity->is_ahu_registered ?? '-',
                            'Koordinat Kantor (GPS Map)' => $applicationIdentity->office_coordinate ?? '-',
                            'NIB' => $applicationIdentity->nib ?? '-',
                            'NPWP' => $applicationIdentity->npwp ?? '-',
                            'Bidang Usaha' => $applicationIdentity->business_field ?? '-',
                        ];

                        $contactFields = [
                            'Nama' => $applicationContacts->name ?? '-',
                            'Nomor Identitas' => $applicationContacts->identity_number ?? '-',
                            'Alamat' => $applicationContacts->address ?? '-',
                            'Email' => $applicationContacts->email ?? '-',
                            'Telepon' => $applicationContacts->phone_number ?? '-',
                            'Jabatan' => $applicationContacts->position ?? '-',
                        ];

                        $companyFields = [
                            'Nama' => $applicationCompany->name ?? '-',
                            'Provinsi' => $applicationCompany->province ?? '-',
                            'Kota' => $applicationCompany->city ?? '-',
                            'Alamat' => $applicationCompany->address ?? '-',
                            'Kode Pos' => $applicationCompany->postal_code ?? '-',
                            'Po. Box' => $applicationCompany->po_box ?? '-',
                            'Telepon' => $applicationCompany->phone_number ?? '-',
                            'Fax' => $applicationCompany->fax ?? '-',
                            'Email' => $applicationCompany->email ?? '-',
                            'Website' => $applicationCompany->website ?? '-',
                            'Perusahaan Afiliasi' => $applicationCompany->affiliate ?? '-',
                        ];

                        $otherFields = [
                            'Produk' => $applicationOtherInformation->products ?? '-',
                            'Kapasitas Produksi' => $applicationOtherInformation->production_capacity ?? '-',
                            'Jumlah Karyawan' => $applicationOtherInformation->number_of_employees ?? '-',
                            'Nama Kantor Cabang' => $applicationOtherInformation->branch_office_name ?? '-',
                            'Jalan Kantor Cabang' => $applicationOtherInformation->branch_office_street ?? '-',
                            'Kota Kantor Cabang' => $applicationOtherInformation->branch_office_city ?? '-',
                            'Kode Pos Kantor Cabang' => $applicationOtherInformation->branch_office_postal_code ?? '-',
                            'Perusahaan Affiliasi' => $applicationOtherInformation->affiliate_company ?? '-',
                            'Jalan Perusahaan Affiliasi' => $applicationOtherInformation->affiliate_company_street ?? '-',
                            'Kota Perusahaan Affiliasi' => $applicationOtherInformation->affiliate_company_city ?? '-',
                            'Kode Pos Perusahaan Affiliasi' => $applicationOtherInformation->affiliate_company_postal_code ?? '-',
                        ];

                        function renderFields($fields) {
                            foreach ($fields as $label => $value) {
                                echo "<tr><td>{$label}</td><td class='px-2'>:</td><td>{$value}</td></tr>";
                            }
                        }
                    @endphp

                    <!-- Konten Tab: Identifikasi Entitas -->

                    <div id="tab-identity" class="tab-section">
                        <h6 class="fw-bold mb-3">A. Identifikasi Entitas</h6>
                        <table style="line-height: 1.8; height: 400px;">
                            {!! renderFields($identityFields) !!}
                        </table>
                    </div>

                    <!-- Konten Tab: Contact Person -->
                    <div id="tab-contact" class="tab-section" style="display: none;">
                        <h6 class="fw-bold mb-3">B. Narahubung</h6>
                        <table style="line-height: 1.8; height: 400px;">
                            {!! renderFields($contactFields) !!}
                        </table>
                    </div>

                    <!-- Konten Tab: Company Detail -->
                    <div id="tab-company" class="tab-section" style="display: none;">
                        <h6 class="fw-bold mb-3">C. Detail Badan Usaha</h6>
                        <table style="line-height: 1.8; height: 400px;">
                            {!! renderFields($companyFields) !!}
                        </table>
                    </div>

                    <!-- Konten Tab: Other Information -->
                    <div id="tab-other" class="tab-section" style="display: none;">
                        <h6 class="fw-bold mb-3">D. Informasi Lainnya</h6>
                        <table style="line-height: 1.8; height: 400px;">
                            {!! renderFields($otherFields) !!}
                        </table>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-6 mb-3">
                <div class="card px-4 pt-4 pb-2 shadow-sm h-100">
                    <h6 class="fw-bold mb-3">Dokumen Permohonan</h6>
                    <select id="documentSelect" class="form-select mb-3" onchange="showDocumentPreview()">
                        <option value="">Pilih Dokumen</option>
                        @foreach ($documents as $name => $path)
                            <option value="{{ asset($path) }}">{{ ucfirst(str_replace('_', ' ', $name)) }}</option>
                        @endforeach
                    </select>

                    <div id="documentPreview" style="display: none;">
                        <iframe id="previewFrame" src="" width="100%" height="400px" class="mb-3 rounded border"></iframe>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="card p-2 rounded-pill">
            <div class="d-flex justify-content-between">
            <a href="{{ route('filament.admin.resources.ncage-applications.index') }}" class="btn btn-outline-dark-red rounded-pill px-4 fw-semibold">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-danger rounded-pill px-4 fw-semibold"
                    onclick="openReasonModal('{{ route('ncage.reject', $recordId) }}', 'Tolak Permohonan', 1)">
                    Tolak Permohonan <i class="fa-solid fa-x ms-2"></i>
                </button>

                <button type="button" class="btn btn-warning rounded-pill px-4 fw-semibold"
                    onclick="openReasonModal('{{ route('ncage.revision', $recordId) }}', 'Kirim Kembali untuk Revisi', 2)">
                    Minta Revisi <i class="fa-solid fa-pencil ms-2"></i>
                </button>

                <form method="POST" action="{{ route('ncage.approve', $recordId) }}">
                    @csrf
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-semibold">
                        Setujui Verifikasi <i class="bi bi-check2-circle ms-1"></i>
                    </button>
                </form>
            </div>
        </div>
        </div>
    </div>

    <!-- Modal: Alasan Penolakan/Revisi -->
    <div class="modal fade" id="reasonModal" tabindex="-1" aria-labelledby="reasonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center rounded-4 p-4">
        <h5 class="fw-bold mb-2" id="reasonModalLabel">Tolak Permohonan?</h5>
        <div class="border border-2 border-dark-red w-100 rounded-pill mb-3"></div>

        <div id="icon-mode" class="p-1 mb-2" style="height: 4rem; width: auto;">
            <div class="display-5 text-dark-red">✕</div>
        </div>

        <form id="reasonForm" method="POST">
            @csrf
            <div class="mb-3 text-start">
            <label id="reasonLabel" for="reasonInput" class="form-label"></label>
            <textarea class="form-control" name="reason" id="reasonInput" rows="3" required></textarea>
            </div>

            <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-outline-dark-red rounded-pill px-4 fw-semibold" data-bs-dismiss="modal">
                <i class="fa-solid fa-arrow-left me-2"></i> Kembali
            </button>
            <button type="submit" class="btn btn-dark-red rounded-pill px-4 fw-semibold text-white" id="submitReasonButton">
                Tolak Permohonan <i class="bi bi-x-circle ms-1"></i>
            </button>
            </div>
        </form>
        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script>
        function showDocumentPreview() {
            const select = document.getElementById('documentSelect');
            const preview = document.getElementById('documentPreview');
            const frame = document.getElementById('previewFrame');

            const fileUrl = select.value;
            if (fileUrl) {
                frame.src = fileUrl;
                preview.style.display = 'block';
            } else {
                frame.src = '';
                preview.style.display = 'none';
            }
        }

        function toggleTabSection(button) {
            const tabId = button.dataset.tab;

            document.querySelectorAll('.tab-section').forEach(el => el.style.display = 'none');
            document.getElementById(`tab-${tabId}`).style.display = 'block';

            document.querySelectorAll('.tab-scroll .btn').forEach(btn => btn.classList.remove('bg-active', 'text-white'));
            button.classList.add('bg-active', 'text-white');
        }

        function openReasonModal(actionUrl, mode, icon) {
            const form = document.getElementById('reasonForm');
            form.action = actionUrl;

            const submitBtn = document.getElementById('submitReasonButton');
            const iconMode = document.getElementById('icon-mode');
            const reasonLabel = document.getElementById('reasonLabel');
            if(icon == 1) {
                submitBtn.innerHTML = mode + ' <span class="ms-2">✕</span>';
                iconMode.innerHTML = '<div class="display-5 text-dark-red">✕</div>';
                reasonLabel.innerText = 'Alasan Penolakan:';
            } else {
                submitBtn.innerHTML = 'Kirim Revisi' + ' <i class="fa-solid fa-paper-plane ms-2"></i>';
                iconMode.innerHTML = '<img src="{{ asset('images/icons/icon-revisi.png') }}" class="img-fluid" style="max-height: 100%; max-width: 100%;" />';
                reasonLabel.innerText = 'Catatan Perbaikan:';
            }
            document.getElementById('reasonModalLabel').innerText = mode + '?';

            const textarea = document.getElementById('reasonInput');
            textarea.value = '';

            const modal = new bootstrap.Modal(document.getElementById('reasonModal'));
            modal.show();
        }
    </script>
@endsection