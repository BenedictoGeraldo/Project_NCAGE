<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\FormNCAGE;
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
            $fields = [
                'surat_permohonan',
                'surat_kebenaran',
                'foto_kantor',
                'sk_domisili',
                'akta_notaris',
                'sk_kemenkumham',
                'siup_nib',
                'company_profile',
                'NPWP',
                'surat_kuasa',
                'sam_gov'
            ];

            // Hapus file
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

            // Upload file baru (pastikan file baru yang diupload diproses)
            foreach ($fields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $filename = $file->getClientOriginalName();
                    $path = "uploads/temp/{$userId}";
                    $file->move(public_path($path), $filename);
                    $data['documents'][$field] = "{$path}/{$filename}";
                }
            }

            // Simpan ulang session
            Session::put('form_ncage', $data);

        } elseif ($request->step == 2) {
            // substep
            if ($request->substep == 1) {
                $data['tanggal_pengajuan'] = $request->tanggal_pengajuan;
                $data['jenis_permohonan'] = $request->jenis_permohonan;
                $data['jenis_permohonan_ncage'] = $request->jenis_permohonan_ncage;
                $data['tujuan_penerbitan'] = $request->tujuan_penerbitan;
                $data['tipe_entitas'] = $request->tipe_entitas;
                $data['status_kepemilikan'] = $request->status_kepemilikan;
                $data['terdaftar_ahu'] = $request->terdaftar_ahu;
                $data['koordinat_kantor'] = $request->koordinat_kantor;
                $data['nib'] = $request->nib;
                $data['npwp'] = $request->npwp;
                $data['bidang_usaha'] = $request->bidang_usaha;
            } elseif ($request->substep == 2) {
                $data['nama_pemohon'] = $request->nama_pemohon;
                $data['no_identitas'] = $request->no_identitas;
                $data['alamat'] = $request->alamat;
                $data['no_tel'] = $request->no_tel;
                $data['email'] = $request->email;
                $data['jabatan'] = $request->jabatan;
            } elseif ($request->substep == 3) {
                $data['nama_badan_usaha'] = $request->nama_badan_usaha;
                $data['provinsi'] = $request->provinsi;
                $data['kota'] = $request->kota;
                $data['alamat_kantor'] = $request->alamat_kantor;
                $data['kode_pos'] = $request->kode_pos;
                $data['po_box'] = $request->po_box;
                $data['no_telp'] = $request->no_telp;
                $data['no_fax'] = $request->no_fax;
                $data['email_kantor'] = $request->email_kantor;
                $data['website_kantor'] = $request->website_kantor;
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
                // 'street_1' => $data['nama_jalan_1'],
                // 'city_1' => $data['kota_1'],
                // 'postal_code_1' => $data['kode_pos_1'],
                // 'affiliate_2' => $data['perusahaan_afiliasi_2'],
                // 'street_2' => $data['nama_jalan_2'],
                // 'city_2' => $data['kota_2'],
                // 'postal_code_2' => $data['kode_pos_2'],
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