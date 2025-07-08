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
        Schema::create('company_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ncage_application_id');
            $table->foreign('ncage_application_id')->references('id')->on('ncage_applications')->onDelete('cascade');

            $table->string('name');
            $table->string('province', 100);
            $table->string('city', 100);
            $table->text('address');
            $table->string('postal_code', 10);
            $table->string('po_box', 20)->nullable();
            $table->string('phone', 20);
            $table->string('fax', 20)->nullable();
            $table->string('email');
            $table->string('website')->nullable();
            $table->string('affiliate')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_details');
    }
};
