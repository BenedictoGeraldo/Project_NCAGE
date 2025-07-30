<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ncage_application_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('q1_kesesuaian_persyaratan'); // 1-4: Tidak Sesuai -> Sangat Sesuai
            $table->tinyInteger('q2_kemudahan_prosedur');      // 1-4: Tidak Mudah -> Sangat Mudah
            $table->tinyInteger('q3_kecepatan_pelayanan');     // 1-4: Tidak Cepat -> Sangat Cepat
            $table->tinyInteger('q4_kewajaran_biaya');         // 1-4: Sangat Mahal -> Gratis
            $table->tinyInteger('q5_kesesuaian_produk');       // 1-4: Tidak Sesuai -> Sangat Sesuai
            $table->tinyInteger('q6_kompetensi_petugas');      // 1-4: Tidak Kompeten -> Sangat Kompeten
            $table->tinyInteger('q7_perilaku_petugas');        // 1-4: Tidak Sopan -> Sangat Sopan
            $table->tinyInteger('q8_kualitas_sarana');         // 1-4: Buruk -> Sangat Baik
            $table->tinyInteger('q9_penanganan_pengaduan');    // 1-4: Tidak Ada -> Dikelola Dengan Baik
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
