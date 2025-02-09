<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('m_faktur', function (Blueprint $table) {
            $table->increments('id_m_faktur'); // Auto-increment primary key
            $table->string('nama_file', 100)->collation('latin1_swedish_ci');
            $table->char('status', 1)->default('t')->collation('latin1_swedish_ci');
            $table->integer('jumlah')->nullable();
            $table->string('lokasi_file', 100)->nullable()->collation('latin1_swedish_ci');
            $table->integer('ukuran')->nullable();
            $table->string('keterangan', 100)->nullable()->collation('latin1_swedish_ci');
            $table->string('userid_created', 20)->nullable()->collation('latin1_swedish_ci');
            $table->dateTime('date_created')->nullable();
            $table->string('userid_modified', 20)->nullable()->collation('latin1_swedish_ci');
            $table->dateTime('date_modified')->nullable();

            $table->unique('id_m_faktur', 'id_m_faktur');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m_faktur');
    }
};
