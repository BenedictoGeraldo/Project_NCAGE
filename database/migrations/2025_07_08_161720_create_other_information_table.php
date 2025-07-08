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
        Schema::create('other_informations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ncage_application_id');
            $table->foreign('ncage_application_id')->references('id')->on('ncage_applications')->onDelete('cascade');

            $table->text('products')->nullable();
            $table->text('production_capacity')->nullable();
            $table->string('number_of_employees', 50)->nullable();
            $table->string('branch_office_name')->nullable();
            $table->text('branch_office_address')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('other_information');
    }
};
