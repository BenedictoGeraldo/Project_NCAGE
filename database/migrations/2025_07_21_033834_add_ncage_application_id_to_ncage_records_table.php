<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Check if the column does not already exist
        if (!Schema::hasColumn('ncage_records', 'ncage_application_id')) {
            Schema::table('ncage_records', function (Blueprint $table) {
                // Add the foreign key column
                // This column will store the ID from the 'ncage_applications' table.
                $table->foreignId('ncage_application_id')
                      ->nullable() // Made nullable to accommodate old records that don't have a related application.
                      ->constrained('ncage_applications') // Creates a foreign key constraint to the 'id' on 'ncage_applications' table.
                      ->onDelete('set null'); // If the related application is deleted, this column's value will be set to NULL.
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Check if the column exists before trying to drop it
        if (Schema::hasColumn('ncage_records', 'ncage_application_id')) {
            Schema::table('ncage_records', function (Blueprint $table) {
                // It's good practice to drop the foreign key constraint before dropping the column
                // The convention for the foreign key name is 'table_column_foreign'
                $table->dropForeign(['ncage_application_id']);
                $table->dropColumn('ncage_application_id');
            });
        }
    }
};
