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
        Schema::create('application_identities', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ncage_application_id');
            $table->foreign('ncage_application_id')->references('id')->on('ncage_applications')->onDelete('cascade');

            $table->date('submission_date');
            $table->string('application_type', 50);
            $table->string('ncage_request_type', 50);
            $table->string('purpose');
            $table->char('entity_type', 1);
            $table->string('building_ownership_status', 50);
            $table->boolean('is_ahu_registered');
            $table->string('office_coordinate');
            $table->string('nib', 100);
            $table->string('npwp', 100);
            $table->text('business_field');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_identities');
    }
};
