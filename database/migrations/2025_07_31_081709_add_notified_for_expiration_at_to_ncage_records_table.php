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
            $table->timestamp('notified_for_expiration_at')->nullable()->after('creation_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ncage_records', function (Blueprint $table) {
            $table->dropColumn('notified_for_expiration_at');
        });
    }
};