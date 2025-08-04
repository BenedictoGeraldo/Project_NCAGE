<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\NcageApplication;
use App\Models\ApplicationIdentity;
use App\Models\ApplicationContact;
use App\Models\CompanyDetail;
use App\Models\OtherInformation;
use App\Models\NcageRecord;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Event;

class FormNCAGEController extends Controller
{
    public function show($step, $substep = 1)
    {
        $userId = auth()->id();
        $isRevisi = NcageApplication::where('user_id', $userId)->where('status_id', 3)->first();

        if ($isRevisi) {
            $rev = Session::get('is_revision')??false;
            if (!$rev) {
                $this->loadRevision($userId);
            }

            return view("form_ncage.index", [
                'step' => 3,
                'revisi' => true,
                'data' => Session::get('form_ncage', []),
            ]);
        }

        if ($step == 1) {
            Session::put('form_ncage_progress', [
                'step' => 1,
            ]);
        }

        $progress = Session::get('form_ncage_progress');
        // Gunakan nilai default jika key substep tidak ada
        $currentStep = $progress['step'];
        $currentSubstep = $progress['substep'] ?? 1;

        // Cegah lompat step
        if ($step > $currentStep) {
            return redirect()->route('pendaftaran-ncage.show', [
                'step' => $currentStep,
                'substep' => $currentSubstep,
            ])->with('error', 'Silakan selesaikan langkah sebelumnya terlebih dahulu.');
        }

        // Cegah lompat substep (hanya jika step sama)
        if ($step == 2 && $substep !== null && $substep > $currentSubstep) {
            return redirect()->route('pendaftaran-ncage.show', [
                'step' => 2,
                'substep' => $currentSubstep,
            ])->with('error', 'Silakan selesaikan sub-langkah sebelumnya terlebih dahulu.');
        }
        return view("form_ncage.index", [
            'step' => $step,
            'substep' => $substep,
            'revisi' => false,
            'data' => Session::get('form_ncage', [])
        ]);
    }

    public function showPerpanjangan()
    {
        $companyName = auth()->user()->company_name;
        $normalizedCompanyName = normalizeCompanyName($companyName);
        
        // Cek apakah user punya NCAGE
        $record = NcageRecord::get()->first(function ($r) use ($normalizedCompanyName) {
            return normalizeCompanyName($r->entity_name) === $normalizedCompanyName;
        });

        // dd($companyName, $normalizedCompanyName, $record);

        if (!$record) {
            return redirect()->route('home')->with('error', 'Data NCAGE lama tidak ditemukan.');
        }

        // Cek apakah user sudah pernah mengisi NcageApplication
        // Ambil semua NcageApplication dan filter berdasarkan companyDetail->name yang sudah dinormalisasi
        $existingApp = NcageApplication::with(['identity', 'contacts', 'companyDetail', 'otherInformation'])
            ->get()
            ->first(function ($app) use ($normalizedCompanyName) {
                return $app->companyDetail &&
                    normalizeCompanyName($app->companyDetail->name) === $normalizedCompanyName;
            });
        
        // dd($normalizedCompanyName, $existingApp);

        if ($existingApp) {
            $this->loadApplication($existingApp);
        } else {
            $this->loadUpdate($record); // seperti sebelumnya
        }

        Session::put('is_perpanjang', true);
        Session::put('form_ncage_progress', ['step' => 1]);

        return redirect()->route('pendaftaran-ncage.show', ['step' => 1]);
    }
    private function loadApplication($application)
    {
        $sessionData = [];

        // === IDENTITY ===
        $identity = $application->identity;
        if ($identity) {
            $sessionData['jenis_permohonan'] = $identity->application_type;
            $sessionData['jenis_permohonan_ncage'] = 2; // <== Selalu diset ke 2 untuk perpanjangan
            $sessionData['tujuan_penerbitan'] = $identity->purpose;
            $sessionData['tujuan_penerbitan_lainnya'] = $identity->other_purpose ?? '-';
            $sessionData['tipe_entitas'] = $identity->entity_type;
            $sessionData['status_kepemilikan'] = $identity->building_ownership_status;
            $sessionData['terdaftar_ahu'] = $identity->is_ahu_registered;
            $sessionData['koordinat_kantor'] = $identity->office_coordinate;
            $sessionData['nib'] = $identity->nib;
            $sessionData['npwp'] = $identity->npwp;
            $sessionData['bidang_usaha'] = $identity->business_field;
        }

        // === CONTACTS ===
        $contact = $application->contacts;
        if ($contact) {
            $sessionData['nama_pemohon'] = $contact->name;
            $sessionData['no_identitas'] = $contact->identity_number;
            $sessionData['alamat'] = $contact->address;
            $sessionData['no_tel'] = $contact->phone_number;
            $sessionData['email'] = $contact->email;
            $sessionData['jabatan'] = $contact->position;
        }

        // === COMPANY DETAIL ===
        $company = $application->companyDetail;
        if ($company) {
            $sessionData['nama_badan_usaha'] = $company->name;
            $sessionData['provinsi'] = $company->province;
            $sessionData['kota'] = $company->city;

            $streets = explode('/', $company->street);
            $sessionData['jalan_1'] = trim($streets[0]);
            $sessionData['jalan_2'] = $streets[1] ?? null;

            $sessionData['kode_pos'] = $company->postal_code;
            $sessionData['po_box'] = $company->po_box;
            $sessionData['no_telp'] = $company->phone;
            $sessionData['no_fax'] = $company->fax;
            $sessionData['email_kantor'] = $company->email;
            $sessionData['website_kantor'] = $company->website;
            $sessionData['perusahaan_afiliasi'] = $company->affiliate;
        }

        // === OTHER INFORMATION ===
        $other = $application->otherInformation;
        if ($other) {
            $sessionData['produk_dihasilkan'] = $other->products;
            $sessionData['kemampuan_produksi'] = $other->production_capacity;
            $sessionData['jumlah_karyawan'] = $other->number_of_employees;

            $sessionData['kantor_cabang_1'] = $other->branch_office_name;
            $sessionData['nama_jalan_1'] = $other->branch_office_street;
            $sessionData['kota_1'] = $other->branch_office_city;
            $sessionData['kode_pos_1'] = $other->branch_office_postal_code;

            $sessionData['perusahaan_afiliasi_2'] = $other->affiliate_company;
            $sessionData['nama_jalan_2'] = $other->affiliate_company_street;
            $sessionData['kota_2'] = $other->affiliate_company_city;
            $sessionData['kode_pos_2'] = $other->affiliate_company_postal_code;
        }

        // === DOCUMENTS ===
        $documents = json_decode($application->documents, true) ?? [];
        $sessionData['documents'] = $documents;

        Session::put('form_ncage', $sessionData);
    }

    private function loadUpdate($application)
    {
        $sessionData = [];

        $sessionData['tipe_entitas'] = $application->toec;
        $streets = explode('/', $application->street);
        $sessionData['jalan_1'] = trim($streets[0]);
        $sessionData['jalan_2'] = $streets[1] ?? null;
        $sessionData['kota'] = $application->city;
        $sessionData['kode_pos'] = $application->psc;
        $sessionData['provinsi'] = $application->stt;
        $sessionData['no_telp'] = $application->tel;
        $sessionData['no_fax'] = $application->fax;
        $sessionData['email_kantor'] = $application->ema;
        $sessionData['website_kantor'] = $application->www;
        $sessionData['po_box'] = $application->pob;

        Session::put('form_ncage', $sessionData);
    }

    private function loadRevision($userId)
    {
        $revision = NcageApplication::with(['identity', 'contacts', 'companyDetail', 'otherInformation'])
            ->where('user_id', $userId)
            ->where('status_id', 3)
            ->first();

        if (!$revision) {
            return; // atau throw exception / redirect
        }

        $sessionData = [];

        // === IDENTITY ===
        $identity = $revision->identity;
        if ($identity) {
            $sessionData['jenis_permohonan'] = $identity->application_type;
            $sessionData['jenis_permohonan_ncage'] = $identity->ncage_request_type;
            $sessionData['tujuan_penerbitan'] = $identity->purpose;
            $sessionData['tujuan_penerbitan_lainnya'] = $identity->other_purpose ?? '-';
            $sessionData['tipe_entitas'] = $identity->entity_type;
            $sessionData['status_kepemilikan'] = $identity->building_ownership_status;
            $sessionData['terdaftar_ahu'] = $identity->is_ahu_registered;
            $sessionData['koordinat_kantor'] = $identity->office_coordinate;
            $sessionData['nib'] = $identity->nib;
            $sessionData['npwp'] = $identity->npwp;
            $sessionData['bidang_usaha'] = $identity->business_field;
        }

        // === CONTACTS ===
        $contact = $revision->contacts; // diasumsikan satu kontak utama
        if ($contact) {
            $sessionData['nama_pemohon'] = $contact->name;
            $sessionData['no_identitas'] = $contact->identity_number;
            $sessionData['alamat'] = $contact->address;
            $sessionData['no_tel'] = $contact->phone_number;
            $sessionData['email'] = $contact->email;
            $sessionData['jabatan'] = $contact->position;
        }

        // === COMPANY DETAIL ===
        $company = $revision->companyDetail;
        if ($company) {
            $sessionData['nama_badan_usaha'] = $company->name;
            $sessionData['provinsi'] = $company->province;
            $sessionData['kota'] = $company->city;

            $streets = explode('/', $company->street);

            // Ambil dan trim jalan_1 dan jalan_2
            $sessionData['jalan_1'] = trim($streets[0]); // Selalu ada
            $sessionData['jalan_2'] = isset($streets[1]) ? trim($streets[1]) : null;

            $sessionData['kode_pos'] = $company->postal_code;
            $sessionData['po_box'] = $company->po_box;
            $sessionData['no_telp'] = $company->phone;
            $sessionData['no_fax'] = $company->fax;
            $sessionData['email_kantor'] = $company->email;
            $sessionData['website_kantor'] = $company->website;
            $sessionData['perusahaan_afiliasi'] = $company->affiliate;
        }

        // === OTHER INFORMATION ===
        $other = $revision->otherInformation;
        if ($other) {
            $sessionData['produk_dihasilkan'] = $other->products;
            $sessionData['kemampuan_produksi'] = $other->production_capacity;
            $sessionData['jumlah_karyawan'] = $other->number_of_employees;

            $sessionData['kantor_cabang_1'] = $other->branch_office_name;
            $sessionData['nama_jalan_1'] = $other->branch_office_street;
            $sessionData['kota_1'] = $other->branch_office_city;
            $sessionData['kode_pos_1'] = $other->branch_office_postal_code;

            $sessionData['perusahaan_afiliasi_2'] = $other->affiliate_company;
            $sessionData['nama_jalan_2'] = $other->affiliate_company_street;
            $sessionData['kota_2'] = $other->affiliate_company_city;
            $sessionData['kode_pos_2'] = $other->affiliate_company_postal_code;
        }

        // === DOKUMEN ===
        $documents = json_decode($revision->documents, true) ?? [];
        $sessionData['documents'] = $documents;

        // Simpan ke session
        Session::put('form_ncage', $sessionData);
        // dd(Session::get('form_ncage'));
        Session::put('form_ncage_progress', ['step' => 3]);
        Session::put('is_revision', true);
    }

    public function handleStep(Request $request)
    {
        // dd(Session::get('form_ncage'));
        // dd($request->all());
        if ($request->has('cancel')) {
            return redirect()->route('home');
        }

        $userId = auth()->user()->id;

        $data = Session::get('form_ncage', []);

        if ($request->step == 1) {
            $wajibFields = [
                'surat_permohonan',
                'surat_kebenaran',
                'foto_kantor',
                'akta_notaris',
                'sk_kemenkumham',
                'siup_nib',
                'company_profile',
                'NPWP',
            ];

            $errors = [];

            foreach ($wajibFields as $field) {
                if (empty($data['documents'][$field])) {
                    $errors[$field] = $field . ' wajib diisi.';
                }
            }

            if (!empty($errors)) {
                return back()->withErrors($errors)->withInput();
            }

            Session::put('form_ncage_progress', ['step' => 2, 'substep' => 1]);

        } elseif ($request->step == 2) {
            // substep
            if ($request->substep == 1) {
                [$rules, $messages, $attributes] = $this->getSubstep1Validation($request);
                $request->validate($rules, $messages, $attributes);

                $fields = array_keys($rules);
                foreach ($fields as $field) {
                    if ($request->has($field)) {
                        $data[$field] = $request->$field;
                    }
                }

                $isPerpanjang = Session::get('is_perpanjang');

                if (!$isPerpanjang) {
                    $data['jenis_permohonan_ncage'] = 1;
                } else {
                    $data['jenis_permohonan_ncage'] = 2;
                }

                Session::put('form_ncage_progress', ['step' => 2, 'substep' => 2]);
            } elseif ($request->substep == 2) {
                [$rules, $messages, $attributes] = $this->getSubstep2Validation();
                $request->validate($rules, $messages, $attributes);

                foreach ($rules as $field => $rule) {
                    $data[$field] = $request->$field;
                }

                $cname = auth()->user()->company_name;
                $data['nama_badan_usaha'] = $cname;

                Session::put('form_ncage_progress', ['step' => 2, 'substep' => 3]);
            } elseif ($request->substep == 3) {
                [$rules, $messages, $attributes] = $this->getSubstep3Validation();
                $request->validate($rules, $messages, $attributes);

                foreach ($rules as $field => $rule) {
                    $data[$field] = $request->$field;
                }

                Session::put('form_ncage_progress', ['step' => 2, 'substep' => 4]);

                Session::put('form_ncage_progress', ['step' => 2, 'substep' => 4]);
            } elseif ($request->substep == 4) {
                $data['produk_dihasilkan'] = $request->produk_dihasilkan;
                $data['kemampuan_produksi'] = $request->kemampuan_produksi;
                $data['jumlah_karyawan'] = $request->jumlah_karyawan;
                $data['kantor_cabang_1'] = $request->kantor_cabang_1;
                $data['nama_jalan_1'] = $request->nama_jalan_1;
                $data['kota_1'] = $request->kota_1;
                $data['kode_pos_1'] = $request->kode_pos_1;
                $data['perusahaan_afiliasi_2'] = $request->perusahaan_afiliasi_2;
                $data['nama_jalan_2'] = $request->nama_jalan_2;
                $data['kota_2'] = $request->kota_2;
                $data['kode_pos_2'] = $request->kode_pos_2;
                Session::put('form_ncage_progress', ['step' => 3]);
            }

        } elseif ($request->step == 3) {

            if (!empty($request->revisi)) {
                // VALIDASI STEP 1 - Dokumen di session
                $docs = Session::get('form_ncage.documents', []);
                $requiredDocs = [
                    'surat_permohonan',
                    'surat_kebenaran',
                    'foto_kantor',
                    'akta_notaris',
                    'sk_kemenkumham',
                    'siup_nib',
                    'company_profile',
                    'NPWP',
                ];
                $missingDocs = [];

                foreach ($requiredDocs as $doc) {
                    if (empty($docs[$doc])) {
                        $missingDocs[$doc] = ucfirst(str_replace('_', ' ', $doc)) . ' wajib diunggah.';
                        Log::warning('Dokumen ' . $doc . ' belum diunggah.');
                    }
                }

                if (!empty($missingDocs)) {
                    return back()->withErrors($missingDocs)->withInput();
                }

                [$rules1, $messages1, $attributes1] = $this->getSubstep1Validation($request);
                [$rules2, $messages2, $attributes2] = $this->getSubstep2Validation();
                [$rules3, $messages3, $attributes3] = $this->getSubstep3Validation();

                $request->validate($rules1 + $rules2 + $rules3, array_merge($messages1, $messages2, $messages3), array_merge($attributes1, $attributes2, $attributes3));

                // Simpan semua kembali
                foreach (array_keys($rules1 + $rules2 + $rules3) as $field) {
                    if ($request->has($field)) {
                        $data[$field] = $request->$field;
                    }
                }

                // Substep 4
                $data['produk_dihasilkan'] = $request->produk_dihasilkan;
                $data['kemampuan_produksi'] = $request->kemampuan_produksi;
                $data['jumlah_karyawan'] = $request->jumlah_karyawan;
                $data['kantor_cabang_1'] = $request->kantor_cabang_1;
                $data['nama_jalan_1'] = $request->nama_jalan_1;
                $data['kota_1'] = $request->kota_1;
                $data['kode_pos_1'] = $request->kode_pos_1;
                $data['perusahaan_afiliasi_2'] = $request->perusahaan_afiliasi_2;
                $data['nama_jalan_2'] = $request->nama_jalan_2;
                $data['kota_2'] = $request->kota_2;
                $data['kode_pos_2'] = $request->kode_pos_2;
            }

            // dd($request->all());
            $finalPath = "uploads/" . Str::slug($data['nama_badan_usaha'], '_'); // Misalnya $userId diambil dari $record->user_id
            // dd($finalPath);

            foreach ($data['documents'] as $field => $path) {
                if (str_contains($path, '/temp/')) {
                    $filename = basename($path);
                    $newPath = "{$finalPath}/{$filename}";

                    $from = public_path($path); // Contoh: public/temp/nama_file.pdf

                    if (file_exists($from)) {
                        // Baca isi file
                        $fileContents = file_get_contents($from);

                        // Simpan ke disk 'public' Laravel Filesystem
                        Storage::disk('public')->put($newPath, $fileContents);

                        // Hapus file asli dari folder temp (opsional)
                        unlink($from);

                        // Simpan path relatif di array
                        $data['documents'][$field] = $newPath;
                    } else {
                        Log::warning("File not found (expected in temp): $from");
                    }
                } else {
                    $data['documents'][$field] = $path;
                }
            }

            // Simpan ke database
            // FormNCAGE::create([
                
            // ])

            $ncageApplication = NcageApplication::updateOrCreate(
                ['user_id' => $userId],
                [
                    'status_id' => 2,
                    'documents' => json_encode($data['documents']),
                ]
            );

            ApplicationIdentity::updateOrCreate(
                ['ncage_application_id' => $ncageApplication->id],
                [
                    'submission_date' => $data['tanggal_pengajuan'],
                    'application_type' => $data['jenis_permohonan'],
                    'ncage_request_type' => $data['jenis_permohonan_ncage'],
                    'purpose' => $data['tujuan_penerbitan'],
                    'entity_type' => $data['tipe_entitas'],
                    'building_ownership_status' => $data['status_kepemilikan'],
                    'is_ahu_registered' => $data['terdaftar_ahu'],
                    'office_coordinate' => $data['koordinat_kantor'],
                    'nib' => $data['nib'],
                    'npwp' => $data['npwp'],
                    'business_field' => $data['bidang_usaha'],
                    'other_purpose' => $data['tujuan_penerbitan'] == 3 ? $data['tujuan_penerbitan_lainnya'] : null,
                ]
            );

            ApplicationContact::updateOrCreate(
                ['ncage_application_id' => $ncageApplication->id],
                [
                    'name' => $data['nama_pemohon'],
                    'identity_number' => $data['no_identitas'],
                    'address' => $data['alamat'],
                    'phone_number' => $data['no_tel'],
                    'email' => $data['email'],
                    'position' => $data['jabatan'],
                ]
            );

            CompanyDetail::updateOrCreate(
                ['ncage_application_id' => $ncageApplication->id],
                [
                    'name' => $data['nama_badan_usaha'],
                    'province' => $data['provinsi'],
                    'city' => $data['kota'],
                    'street' => trim($data['jalan_1'] . ' / ' . ($data['jalan_2'] ?? '')),
                    'postal_code' => $data['kode_pos'],
                    'po_box' => $data['po_box'],
                    'phone' => $data['no_telp'],
                    'fax' => $data['no_fax'],
                    'email' => $data['email_kantor'],
                    'website' => $data['website_kantor'],
                    'affiliate' => $data['perusahaan_afiliasi'],
                ]
            );

            OtherInformation::updateOrCreate(
                ['ncage_application_id' => $ncageApplication->id],
                [
                    'products' => $data['produk_dihasilkan'],
                    'production_capacity' => $data['kemampuan_produksi'],
                    'number_of_employees' => $data['jumlah_karyawan'],
                    'branch_office_name' => $data['kantor_cabang_1'],
                    'branch_office_street' => $data['nama_jalan_1'],
                    'branch_office_city' => $data['kota_1'],
                    'branch_office_postal_code' => $data['kode_pos_1'],
                    'affiliate_company' => $data['perusahaan_afiliasi_2'],
                    'affiliate_company_street' => $data['nama_jalan_2'],
                    'affiliate_company_city' => $data['kota_2'],
                    'affiliate_company_postal_code' => $data['kode_pos_2'],
                ]
            );

            //Notifikasi form ncage telah dikirim
            /** @var \App\Models\User $user */
            $user = auth()->user();

            //kirim notifikasi bahwa permohonan telah berhasil dikirim
            if ($user) {
                // 1. Notifikasi untuk RIWAYAT (disimpan ke database)
                $user->notify(new \App\Notifications\ApplicationSubmitted($ncageApplication));

                // 2. Notifikasi untuk REAL-TIME (dikirim via Pusher)
                $title = 'Permohonan Terkirim';
                $message = 'Permohonan NCAGE Anda telah berhasil kami terima.';
                Event::dispatch(new \App\Events\UserNotificationEvent($user, $title, $message));
            }

            Session::forget('form_ncage');
            Session::flash('submit_success', true);
            Session::flash('form_submitted', true);
            return redirect()->route('pendaftaran-ncage.show', ['step' => 3]);
        }

        // Simpan session sementara
        Session::put('form_ncage', $data);

        Log::info('Session Data: ' . json_encode(session()->all()));

        // Redirect ke view sesuai step dan sub_step selanjutnya
        if ($request->step == 2 && $request->substep < 4) {
            return redirect()->route('pendaftaran-ncage.show', ['step' => 2, 'substep' => $request->substep + 1]);
        } elseif ($request->step == 2 && $request->substep == 4) {
            // selesai step 2, lanjut ke step 3
            return redirect()->route('pendaftaran-ncage.show', ['step' => 3]);
        } else {
            return redirect()->route('pendaftaran-ncage.show', ['step' => $request->step + 1]);
        }
    }

    private function getSubstep1Validation(Request $request)
    {
        $rules = [
            'tanggal_pengajuan' => 'nullable|date|after_or_equal:today',
            'jenis_permohonan' => 'required|string',
            // 'jenis_permohonan_ncage' => 'required|string',
            'tujuan_penerbitan' => 'required|in:1,2,3',
            'tipe_entitas' => 'required|in:E,F,G,H',
            'status_kepemilikan' => 'required|in:1,2,3',
            'terdaftar_ahu' => 'required|string',
            'koordinat_kantor' => 'required|string',
            'nib' => 'required|string',
            'npwp' => 'required|string',
            'bidang_usaha' => 'required|string',
        ];

        if ($request->tujuan_penerbitan == '3') {
            $rules['tujuan_penerbitan_lainnya'] = 'required|string|max:255';
        }

        $messages = [
            'required' => ':attribute wajib diisi.',
            'in' => ':attribute tidak valid.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'after_or_equal' => ':attribute harus berisi hari ini atau setelah hari ini.',
        ];

        $attributes = [
            'tanggal_pengajuan' => 'Tanggal Pengajuan',
            'jenis_permohonan' => 'Jenis Permohonan',
            // 'jenis_permohonan_ncage' => 'Jenis Permohonan NCAGE',
            'tujuan_penerbitan' => 'Tujuan Penerbitan',
            'tujuan_penerbitan_lainnya' => 'Tujuan Penerbitan (Lainnya)',
            'tipe_entitas' => 'Tipe Entitas',
            'status_kepemilikan' => 'Status Kepemilikan Bangunan',
            'terdaftar_ahu' => 'Status AHU',
            'koordinat_kantor' => 'Koordinat Kantor',
            'nib' => 'NIB',
            'npwp' => 'NPWP',
            'bidang_usaha' => 'Bidang Usaha',
        ];

        return [$rules, $messages, $attributes];
    }

    private function getSubstep2Validation()
    {
        $rules = [
            'nama_pemohon' => 'required|string|max:255',
            'no_identitas' => 'required|string|max:16',
            'alamat' => 'required|string|max:255',
            'no_tel' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'jabatan' => 'nullable|string|max:100',
        ];

        $messages = [
            'required' => ':attribute wajib diisi.',
            'email' => ':attribute harus berupa alamat email yang valid.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
        ];

        $attributes = [
            'nama_pemohon' => 'Nama Pemohon',
            'no_identitas' => 'Nomor Identitas',
            'alamat' => 'Alamat',
            'no_tel' => 'Nomor Telepon / HP',
            'email' => 'Email Pemohon',
            'jabatan' => 'Jabatan',
        ];

        return [$rules, $messages, $attributes];
    }

    private function getSubstep3Validation()
    {
        $rules = [
            'nama_badan_usaha'     => 'required|string|max:255',
            'provinsi'             => 'required|string|max:100',
            'kota'                 => 'required|string|max:100',
            'jalan_1'              => 'required|string|max:78',
            'jalan_2'              => 'nullable|string|max:78',
            'kode_pos'             => 'required|string|max:10',
            'po_box'               => 'nullable|string|max:50',
            'no_telp'              => 'required|string|max:20',
            'no_fax'               => 'required|string|max:20',
            'email_kantor'         => 'required|email|max:255',
            'website_kantor'       => 'nullable|url|max:255',
            'perusahaan_afiliasi'  => 'nullable|string|max:255',
        ];

        $messages = [
            'required' => ':attribute wajib diisi.',
            'email' => ':attribute harus berupa alamat email yang valid.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
            'url' => ':attribute harus berupa alamat URL yang valid.',
        ];

        $attributes = [
            'nama_badan_usaha'     => 'Nama Badan Usaha',
            'provinsi'             => 'Provinsi',
            'kota'                 => 'Kota',
            'jalan_1'              => 'Jalan 1',
            'jalan_2'              => 'Jalan 2',
            'kode_pos'             => 'Kode Pos',
            'po_box'               => 'PO.Box',
            'no_telp'              => 'No. Telepon Kantor',
            'no_fax'               => 'No. Fax Kantor',
            'email_kantor'         => 'Email Kantor',
            'website_kantor'       => 'Website Kantor',
            'perusahaan_afiliasi'  => 'Perusahaan Afiliasi',
        ];

        return [$rules, $messages, $attributes];
    }

    public function uploadTemp(Request $request)
    {
        $userId = auth()->id();
        $field = $request->input('field');

        // Validasi file
        try {
        $request->validate([
                'file' => 'required|mimes:pdf|max:5120',
            ], [
                'file.required' => 'File wajib diunggah.',
                'file.mimes' => 'File harus berupa PDF.',
                'file.max' => 'Ukuran file maksimal 5MB.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first('file'),
            ], 422);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();

            $companyName = auth()->user()->company_name ?? 'company';
            $companyName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $companyName);

            $filename = "{$field}_{$companyName}.{$extension}";
            $path = "uploads/temp/{$userId}";
            $relativePath = "{$path}/{$filename}";

            $file->move(public_path($path), $filename);

            // Simpan langsung ke session form_ncage['documents']
            $data = Session::get('form_ncage', []);
            $data['documents'][$field] = $relativePath;
            Session::put('form_ncage', $data);

            return response()->json([
                'success' => true,
                'filename' => $filename,
                'path' => $relativePath,
                'session' => Session::get('form_ncage')  // Kirim session
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tidak ada file yang diunggah.'
        ], 422);
    }

    public function removeFile(Request $request)
    {
        $field = $request->input('field');
        $data = session('form_ncage', []);
        $filePath = $data['documents'][$field] ?? null;

        if ($filePath && file_exists(public_path($filePath))) {
            unlink(public_path($filePath));
        }

        // Hapus dari session
        unset($data['documents'][$field]);
        session(['form_ncage' => $data]);

        return response()->json(['success' => true]);
    }
    function normalizeCompanyName($name)
    {
        $name = preg_replace('/^(pt|cv|ud|pd|perum|perusahaan|persero)\.?[\s]+/i', '', $name);
        $name = preg_replace('/[^a-z0-9]/i', '', $name);
        return strtolower($name);
    }

    public function showSuratPermohonan()
    {
        return view('form_ncage.template_docs.surat_permohonan');
    }

    public function downloadSuratPermohonan()
    {
        $pdf = PDF::loadView('form_ncage.template_docs.surat_permohonan');
        return $pdf->download('surat_permohonan.pdf');
    }

    public function showSuratPernyataan() {
        return view('form_ncage.template_docs.surat_pernyataan');
    }

    public function downloadSuratPernyataan() {
        $pdf = PDF::loadView('form_ncage.template_docs.surat_pernyataan');
        return $pdf->download('surat_pernyataan.pdf');
    }
}