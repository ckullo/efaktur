<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('m_code_mat', function (Blueprint $table) {
            $table->id('id_m_code_mat'); // Auto-increment primary key
            $table->string('nama_code_mat', 2)->unique(); // Unique constraint
            $table->char('status', 1)->default('t'); // Status field
            $table->string('keterangan', 200)->nullable(); // Description field
            $table->string('userid_created', 20)->nullable();
            $table->dateTime('date_created')->nullable();
            $table->string('userid_modified', 20)->nullable();
            $table->dateTime('date_modified')->nullable();

            // Additional indexes
            $table->index('nama_code_mat', 'idx3'); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_code_mat');
    }
};
