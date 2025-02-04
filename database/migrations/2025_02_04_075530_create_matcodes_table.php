<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('m_matcode', function (Blueprint $table) {
            $table->id('id_m_matcode'); // Auto-increment primary key
            $table->unsignedInteger('id_m_matcode_file'); // Foreign key reference
            $table->string('nama', 30)->unique(); // Unique constraint
            $table->char('status', 1)->default('t'); // Status field
            $table->string('description', 100)->nullable(); // Optional description
            $table->string('userid_created', 20)->nullable();
            $table->dateTime('date_created')->nullable();
            $table->string('userid_modified', 20)->nullable();
            $table->dateTime('date_modified')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_matcode');
    }
};
