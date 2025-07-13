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
        Schema::table('other_informations', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn('branch_office_address');

            // Tambah kolom baru setelah 'branch_office_name'
            $table->string('branch_office_street')->nullable()->after('branch_office_name');
            $table->string('branch_office_city')->nullable()->after('branch_office_street');
            $table->string('branch_office_postal_code')->nullable()->after('branch_office_city');

            $table->string('affiliate_company')->nullable()->after('branch_office_postal_code');
            $table->string('affiliate_company_street')->nullable()->after('affiliate_company');
            $table->string('affiliate_company_city')->nullable()->after('affiliate_company_street');
            $table->string('affiliate_company_postal_code')->nullable()->after('affiliate_company_city');
        });
    }

    public function down(): void
    {
        Schema::table('other_informations', function (Blueprint $table) {
            $table->dropColumn([
                'branch_office_street',
                'branch_office_city',
                'branch_office_postal_code',
                'affiliate_company',
                'affiliate_company_street',
                'affiliate_company_city',
                'affiliate_company_postal_code',
            ]);

            $table->text('branch_office_address')->nullable()->after('branch_office_name');
        });
    }
};
