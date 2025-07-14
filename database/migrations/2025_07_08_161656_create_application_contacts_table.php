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
        Schema::create('application_contacts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ncage_application_id');
            $table->foreign('ncage_application_id')->references('id')->on('ncage_applications')->onDelete('cascade');

            $table->string('name');
            $table->string('identity_number', 50);
            $table->text('address');
            $table->string('phone_number', 20);
            $table->string('email');
            $table->string('position', 100)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_contacts');
    }
};
