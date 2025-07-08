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
        Schema::create('ncage_applications', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('restrict');

            $table->json('documents')->comment('Menyimpan path ke 11 file dokumen');
            $table->text('revision_notes')->nullable()->comment('Catatan dari admin jika butuh perbaikan');

            $table->string('ncage_code', 10)->nullable();
            $table->string('international_certificate_path')->nullable();
            $table->string('domestic_certificate_path')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ncage_applications');
    }
};
