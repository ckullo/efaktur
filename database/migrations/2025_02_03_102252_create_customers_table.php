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

        Schema::create('m_customer_detail', function (Blueprint $table) {
            $table->id('id_m_customer_detail'); // Primary key
            $table->unsignedInteger('id_m_customer')->nullable();
            $table->string('kode', 15)->nullable();
            $table->string('nama', 100)->nullable();
            $table->string('alamat', 200)->nullable();
            $table->string('kota', 200)->nullable();
            $table->string('kode_pos', 20)->nullable();
            $table->char('status', 1)->default('t');

            // Unique indexes
            $table->unique(['id_m_customer', 'kode'], 'idx_productcode_customer');

            // Index for searching
            $table->index('nama', 'idx_customer_detail_nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_customer');
        Schema::dropIfExists('m_customer_detail');
    }
};
