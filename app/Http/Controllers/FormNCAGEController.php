<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\NcageApplication;
use App\Models\ApplicationIdentity;
use App\Models\ApplicationContact;
use App\Models\CompanyDetail;
use App\Models\OtherInformation;
use Barryvdh\DomPDF\Facade\Pdf;

class FormNCAGEController extends Controller
{
    public function show($step, $substep = 1)
    {
        return view("form_ncage.index", [
            'step' => $step,
            'substep' => $substep,
            'data' => Session::get('form_ncage', []),
        ]);
    }

    public function handleStep(Request $request)
    {
        // dd(Session::get('form_ncage'));
        // dd($request->all());
        if ($request->has('cancel')) {
            return redirect()->route('home');
        }

        $userId = auth()->user()->id;
        $cname = auth()->user()->company_name;

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

            $optionalFields = [
                'sk_domisili',
                'surat_kuasa',
                'sam_gov',
            ];

            $allFields = array_merge($wajibFields, $optionalFields);

            // Hapus file jika diminta
            if ($request->has('hapus_file')) {
                foreach ($request->hapus_file as $hapusField) {
                    if (!empty($data['documents'][$hapusField])) {
                        $filePath = public_path($data['documents'][$hapusField]);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                        unset($data['documents'][$hapusField]); // Hapus dari session
                    }
                }
            }

            // Simpan ulang ke session
            Session::put('form_ncage', $data);

            // Validasi file
            $rules = [];
            foreach ($wajibFields as $field) {
                if (empty($data['documents'][$field])) {
                    $rules[$field] = 'required|mimes:pdf|max:5120'; // Wajib kalau belum ada di session
                } else {
                    $rules[$field] = 'nullable|mimes:pdf|max:5120'; // Tidak wajib jika sudah ada di session
                }
            }
            foreach ($optionalFields as $field) {
                $rules[$field] = 'nullable|mimes:pdf|max:5120';
            }

            // Label alias untuk nama field
            $attributes = [
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

            // Pesan error custom dalam bahasa Indonesia
            $messages = [
                'required' => ':attribute wajib diisi.',
                'mimes' => ':attribute harus berupa file PDF.',
                'max' => ':attribute tidak boleh lebih dari 5MB.',
            ];

            // Jalankan validasi
            $request->validate($rules, $messages, $attributes);

            // Upload file baru
            $companyName = \App\Models\User::find($userId)?->company_name ?? 'company';
            $companyName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $companyName); // Bersihkan nama

            foreach ($allFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $extension = $file->getClientOriginalExtension();
                    $filename = "{$field}_{$companyName}.{$extension}";

                    $path = "uploads/temp/{$userId}";
                    $file->move(public_path($path), $filename);

                    $data['documents'][$field] = "{$path}/{$filename}";
                }
            }

        } elseif ($request->step == 2) {
            // substep
            if ($request->substep == 1) {
                $wajibFields = [
                    'jenis_permohonan',
                    'jenis_permohonan_ncage',
                    'tujuan_penerbitan',
                    'tipe_entitas',
                    'status_kepemilikan',
                    'terdaftar_ahu',
                    'koordinat_kantor',
                    'nib',
                    'npwp',
                    'bidang_usaha',
                ];

                // Validasi rules
                $rules = [
                    'tanggal_pengajuan' => 'nullable|date',
                    'jenis_permohonan' => 'required|string',
                    'jenis_permohonan_ncage' => 'required|string',
                    'tujuan_penerbitan' => 'required|in:1,2,3',
                    'tipe_entitas' => 'required|in:E,F,G,H',
                    'status_kepemilikan' => 'required|in:1,2,3',
                    'terdaftar_ahu' => 'required|string',
                    'koordinat_kantor' => 'required|string',
                    'nib' => 'required|string',
                    'npwp' => 'required|string',
                    'bidang_usaha' => 'required|string',
                ];

                // Jika tujuan_penerbitan == 3 (lainnya), maka field tambahan harus diisi
                if ($request->tujuan_penerbitan == '3') {
                    $rules['tujuan_penerbitan_lainnya'] = 'required|string|max:255';
                }

                // Label alias
                $attributes = [
                    'tanggal_pengajuan' => 'Tanggal Pengajuan',
                    'jenis_permohonan' => 'Jenis Permohonan',
                    'jenis_permohonan_ncage' => 'Jenis Permohonan NCAGE',
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

                // Pesan error custom
                $messages = [
                    'required' => ':attribute wajib diisi.',
                    'in' => ':attribute tidak valid.',
                    'string' => ':attribute harus berupa teks.',
                    'max' => ':attribute tidak boleh lebih dari :max karakter.',
                    'date' => ':attribute harus berupa tanggal yang valid.',
                ];

                // Jalankan validasi
                $request->validate($rules, $messages, $attributes);

                // Simpan ke session jika valid
                foreach ($wajibFields as $field) {
                    $data[$field] = $request->$field;
                }
                $data['tanggal_pengajuan'] = $request->tanggal_pengajuan;

                // Jika ada input tambahan untuk tujuan_penerbitan lainnya
                if ($request->tujuan_penerbitan == '3') {
                    $data['tujuan_penerbitan_lainnya'] = $request->tujuan_penerbitan_lainnya;
                }
            } elseif ($request->substep == 2) {
                $rules = [
                    'nama_pemohon' => 'required|string|max:255',
                    'no_identitas' => 'required|string|max:50',
                    'alamat' => 'required|string|max:255',
                    'no_tel' => 'required|string|max:20',
                    'email' => 'required|email|max:255',
                    'jabatan' => 'nullable|string|max:100',
                ];

                $attributes = [
                    'nama_pemohon' => 'Nama Pemohon',
                    'no_identitas' => 'Nomor Identitas',
                    'alamat' => 'Alamat',
                    'no_tel' => 'Nomor Telepon / HP',
                    'email' => 'Email Pemohon',
                    'jabatan' => 'Jabatan',
                ];

                $messages = [
                    'required' => ':attribute wajib diisi.',
                    'email' => ':attribute harus berupa alamat email yang valid.',
                    'max' => ':attribute tidak boleh lebih dari :max karakter.',
                ];

                // Jalankan validasi
                $request->validate($rules, $messages, $attributes);

                // Simpan ke session jika valid
                $data['nama_pemohon'] = $request->nama_pemohon;
                $data['no_identitas'] = $request->no_identitas;
                $data['alamat'] = $request->alamat;
                $data['no_tel'] = $request->no_tel;
                $data['email'] = $request->email;
                $data['jabatan'] = $request->jabatan;
            } elseif ($request->substep == 3) {
                $rules = [
                    'nama_badan_usaha'     => 'required|string|max:255',
                    'provinsi'             => 'required|string|max:100',
                    'kota'                 => 'required|string|max:100',
                    'alamat_kantor'        => 'required|string|max:500',
                    'kode_pos'             => 'required|string|max:10',
                    'po_box'               => 'nullable|string|max:50',
                    'no_telp'              => 'required|string|max:20',
                    'no_fax'               => 'required|string|max:20',
                    'email_kantor'         => 'required|email|max:255',
                    'website_kantor'       => 'nullable|string|max:255',
                    'perusahaan_afiliasi'  => 'nullable|string|max:255',
                ];

                $attributes = [
                    'nama_badan_usaha'     => 'Nama Badan Usaha',
                    'provinsi'             => 'Provinsi',
                    'kota'                 => 'Kota',
                    'alamat_kantor'        => 'Alamat Kantor',
                    'kode_pos'             => 'Kode Pos',
                    'po_box'               => 'PO.Box',
                    'no_telp'              => 'No. Telepon Kantor',
                    'no_fax'               => 'No. Fax Kantor',
                    'email_kantor'         => 'Email Kantor',
                    'website_kantor'       => 'Website Kantor',
                    'perusahaan_afiliasi'  => 'Perusahaan Afiliasi',
                ];

                $messages = [
                    'required' => ':attribute wajib diisi.',
                    'email' => ':attribute harus berupa alamat email yang valid.',
                    'max' => ':attribute tidak boleh lebih dari :max karakter.',
                ];

                // Jalankan validasi
                $request->validate($rules, $messages, $attributes);

                // Simpan ke session
                $data['nama_badan_usaha']     = $request->nama_badan_usaha;
                $data['provinsi']             = $request->provinsi;
                $data['kota']                 = $request->kota;
                $data['alamat_kantor']        = $request->alamat_kantor;
                $data['kode_pos']             = $request->kode_pos;
                $data['po_box']               = $request->po_box;
                $data['no_telp']              = $request->no_telp;
                $data['no_fax']               = $request->no_fax;
                $data['email_kantor']         = $request->email_kantor;
                $data['website_kantor']       = $request->website_kantor;
                $data['perusahaan_afiliasi'] = $request->perusahaan_afiliasi;
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
            }
        } elseif ($request->step == 3) {
            $finalPath = "uploads/{$userId}";
            if (!file_exists(public_path($finalPath))) {
                mkdir(public_path($finalPath), 0755, true);
            }

            // Pindahkan file dari temp ke folder final
            foreach ($data['documents'] as $field => $path) {
                $newPath = "{$finalPath}/" . basename($path);
                rename(public_path($path), public_path($newPath));
                $data['documents'][$field] = $newPath;
            }

            // Simpan ke database
            // FormNCAGE::create([
                
            // ])

            $ncageApplication = NcageApplication::create([
                'user_id' => $userId,
                'status_id' => 3,
                'documents' => json_encode($data['documents'])
            ]);

            ApplicationIdentity::create([
                'ncage_application_id' => $ncageApplication->id,
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
                'created_at' => now(),
                'updated_at' => now()
            ]);

            ApplicationContact::create([
                'ncage_application_id' => $ncageApplication->id,
                'name' => $data['nama_pemohon'],
                'identity_number' => $data['no_identitas'],
                'address' => $data['alamat'],
                'phone_number' => $data['no_tel'],
                'email' => $data['email'],
                'position' => $data['jabatan'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            CompanyDetail::create([
                'ncage_application_id' => $ncageApplication->id,
                'name' => $data['nama_badan_usaha'],
                'province' => $data['provinsi'],
                'city' => $data['kota'],
                'address' => $data['alamat_kantor'],
                'postal_code' => $data['kode_pos'],
                'po_box' => $data['po_box'],
                'phone' => $data['no_telp'],
                'fax' => $data['no_fax'],
                'email' => $data['email_kantor'],
                'website' => $data['website_kantor'],
                'affiliate' => $data['perusahaan_afiliasi'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            OtherInformation::create([
                'ncage_application_id' => $ncageApplication->id,
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
                'created_at' => now(),
                'updated_at' => now()
            ]);

            Session::forget('form_ncage');
            return redirect()->route('pendaftaran-ncage.show', ['step' => 1])->with('success', 'Data berhasil disimpan!');
        }

        // Simpan session sementara
        Session::put('form_ncage', $data);

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