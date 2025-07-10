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
        Schema::create('ncage_records', function (Blueprint $table) {
            $table->id();
            $table->string('ncage_code', 10)->unique()->comment('NCAGE');
            $table->string('ncagesd', 5)->nullable()->comment('NCAGESD');
            $table->string('toec', 5)->nullable()->comment('TOEC');
            $table->string('entity_name')->comment('Entity Name');
            $table->text('street')->nullable()->comment('Street (ST1/2)');
            $table->string('city')->nullable()->comment('City (CIT)');
            $table->string('psc', 20)->nullable()->comment('Post Code, Physical Address (PSC)');
            $table->string('country')->nullable()->comment('Country');
            $table->string('ctr', 10)->nullable()->comment('ISO (CTR)');
            $table->string('stt')->nullable()->comment('State/Province (STT)');
            $table->string('ste', 10)->nullable()->comment('FIPS State (STE)');
            $table->boolean('is_sam_requested')->default(false)->comment('Cage code requested for SAM');
            $table->text('remarks')->nullable()->comment('Remarks');
            $table->date('last_change_date_international')->nullable()->comment('Date Last Change International');
            $table->dateTime('change_date')->nullable()->comment('Change Date');
            $table->dateTime('creation_date')->nullable()->comment('Creation Date');
            $table->dateTime('load_date')->nullable()->comment('Load Date');
            $table->string('national')->nullable()->comment('National');
            $table->string('nac')->nullable()->comment('NAC');
            $table->string('idn')->nullable()->comment('IDN');
            $table->string('bar')->nullable()->comment('BAR');
            $table->string('nai')->nullable()->comment('NAI');
            $table->string('cpv')->nullable()->comment('CPV');
            $table->string('uns')->nullable()->comment('UNS');
            $table->string('sic')->nullable()->comment('SIC');
            $table->string('tel')->nullable()->comment('Voice telephone number (TEL)');
            $table->string('fax')->nullable()->comment('Telefax number (FAX)');
            $table->string('ema')->nullable()->comment('Email (EMA)');
            $table->string('www')->nullable()->comment('WWW (WWW)');
            $table->string('pob')->nullable()->comment('Post Office Box Number (POB)');
            $table->string('pcc')->nullable()->comment('City, Postal Address (PCC)');
            $table->string('pcs')->nullable()->comment('Post Code, Postal Address (PCS)');
            $table->string('rp1_5')->nullable()->comment('Replaced By (RP1-5)');
            $table->integer('nmcrl_ref_count')->nullable()->comment('NMCRL Reference count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ncage_records');
    }
};
