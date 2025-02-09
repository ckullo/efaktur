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
        Schema::create('m_loading', function (Blueprint $table) {
            $table->increments('id_m_loading'); // Auto-increment primary key
            $table->integer('no_')->nullable();
            $table->string('nama_file_faktur', 100)->nullable()->collation('latin1_swedish_ci');
            $table->string('nama)file_sales', 100)->nullable()->collation('latin1_swedish_ci');
            $table->string('nama_file_csv', 100)->nullable()->collation('latin1_swedish_ci');
            $table->string('periode',6)->collation('latin1_swedish_ci');
            $table->string('status', 1)->default('t')->collation('latin1_swedish_ci');
            $table->integer('jumlah')->nullable();
            $table->string('lokasi_file', 100)->nullable()->collation('latin1_swedish_ci');
            $table->integer('ukuran')->nullable();
            $table->string('keterangan', 100)->nullable()->collation('latin1_swedish_ci');
            $table->string('userid_created', 20)->nullable()->collation('latin1_swedish_ci');
            $table->dateTime('date_created')->nullable();
            $table->string('userid_modified', 20)->nullable()->collation('latin1_swedish_ci');
            $table->dateTime('date_modified')->nullable();
            $table->dateTime('date_approve')->nullable();

            $table->unique('id_m_loading', 'id_m_loading');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_loading');
    }
};
