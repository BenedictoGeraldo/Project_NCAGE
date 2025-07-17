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
        Schema::table('ncage_records', function (Blueprint $table) {
            $table->string('domestic_certificate_path')->nullable()->after('nmcrl_ref_count');
            $table->string('international_certificate_path')->nullable()->after('domestic_certificate_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ncage_records', function (Blueprint $table) {
            $table->dropColumn(['domestic_certificate_path', 'international_certificate_path']);
        });
    }
};
