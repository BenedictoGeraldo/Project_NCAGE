<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ncage_applications', function (Blueprint $table) {
            $table->foreignId('verified_by')
                ->nullable()
                ->after('domestic_certificate_path')
                ->constrained('admins')
                ->nullOnDelete();

            $table->foreignId('validated_by')
                ->nullable()
                ->after('verified_by')
                ->constrained('admins')
                ->nullOnDelete();

            $table->foreignId('revision_by')
                ->nullable()
                ->after('validated_by')
                ->constrained('admins')
                ->nullOnDelete();

            $table->foreignId('rejected_by')
                ->nullable()
                ->after('revision_by')
                ->constrained('admins')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ncage_applications', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['validated_by']);
            $table->dropForeign(['revision_by']);
            $table->dropForeign(['rejected_by']);

            $table->dropColumn([
                'verified_by',
                'validated_by',
                'revision_by',
                'rejected_by',
            ]);
        });
    }
};
