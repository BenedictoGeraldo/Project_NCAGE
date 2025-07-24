<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_identities', function (Blueprint $table) {
            $table->string('other_purpose', 255)->nullable()->after('purpose');
        });
    }

    public function down(): void
    {
        Schema::table('application_identities', function (Blueprint $table) {
            $table->dropColumn('other_purpose');
        });
    }
};