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
        Schema::create('m_vat', function (Blueprint $table) {
            $table->id('id_m_vat'); // Primary Key
            $table->string('nilai_vat', 5);
            $table->char('status', 1)->default('t');
            $table->string('userid_created', 20)->nullable();
            $table->string('note', 50)->nullable();
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
        Schema::dropIfExists('m_vat');
    }
};
