<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('m_matcode_file', function (Blueprint $table) {
            $table->id('id_m_matcode_file'); // Auto-increment primary key
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

            $table->unique('id_m_matcode_file');
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_matcode_file');
    }
};
