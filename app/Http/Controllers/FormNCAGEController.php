<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FormNCAGEController extends Controller
{
    public function show($step)
    {
        return view("form_ncage.index", [
            'step' => $step,
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
            $data['email'] = $request->input('email');
            $data['nama'] = $request->input('nama');
            // Simpan data lainnya di step 2
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

            // Simpan ke database (contoh):
            // YourModel::create([
            //     'user_id' => $userId,
            //     'nama' => $data['nama'],
            //     'email' => $data['email'],
            //     'documents' => json_encode($data['documents'])
            // ]);

            Session::forget('form_ncage');
            return redirect()->route('pendaftaran-ncage.show', ['step' => 1])->with('success', 'Data berhasil disimpan!');
        }

        // Simpan session sementara
        Session::put('form_ncage', $data);

        return redirect()->route('pendaftaran-ncage.show', ['step' => $request->step + 1]);
    }
}