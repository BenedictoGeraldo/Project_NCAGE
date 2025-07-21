<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_details', function (Blueprint $table) {
            // Rename column
            $table->renameColumn('address', 'street');
        });

        // Add comment after rename
        Schema::table('company_details', function (Blueprint $table) {
            $table->text('street')->comment('Street(1/2)')->change();
        });
    }

    public function down(): void
    {
        Schema::table('company_details', function (Blueprint $table) {
            // Remove comment and rename back
            $table->renameColumn('street', 'address');
        });

        Schema::table('company_details', function (Blueprint $table) {
            $table->text('address')->comment(null)->change();
        });
    }
};
