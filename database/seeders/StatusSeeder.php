<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('statuses')->insert([
            ['id' => 1, 'name' => 'Permohonan Dikirim', 'description' => 'Status awal setelah permohonan berhasil dikirim.'],
            ['id' => 2, 'name' => 'Verifikasi Berkas & Data', 'description' => 'Status saat admin sedang memeriksa kelengkapan dan kesesuaian data.'],
            ['id' => 3, 'name' => 'Butuh Perbaikan', 'description' => 'Status jika admin menolak dan meminta pemohon untuk melakukan perbaikan data atau dokumen.'],
            ['id' => 4, 'name' => 'Proses Validasi', 'description' => 'Status setelah verifikasi berkas berhasil dan data sedang diproses lebih lanjut.'],
            ['id' => 5, 'name' => 'Sertifikat Diterbitkan', 'description' => 'Status akhir jika permohonan disetujui dan sertifikat telah terbit.'],
        ]);
    }
}
