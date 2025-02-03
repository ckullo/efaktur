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
        Schema::create('m_customer', function (Blueprint $table) {
            $table->id('id_m_customer'); // Primary Key
            $table->string('nama_file', 100);
            $table->char('status', 1)->default('t');
            $table->integer('jumlah')->nullable();
            $table->string('lokasi_file', 100)->nullable();
            $table->integer('ukuran')->nullable();
            $table->string('keterangan', 100)->nullable();
            $table->string('userid_created', 20)->nullable();
            $table->dateTime('date_created')->nullable();
            $table->string('userid_modified', 20)->nullable();
            $table->dateTime('date_modified')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_customer');
    }
};
