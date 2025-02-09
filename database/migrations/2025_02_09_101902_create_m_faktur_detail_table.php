<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('m_faktur_detail', function (Blueprint $table) {
            $table->increments('id_m_faktur_detail'); // Auto-increment primary key
            $table->unsignedInteger('id_m_loading')->nullable();
            $table->string('faktur_no', 35)->nullable()->collation('latin1_swedish_ci');
            $table->string('bill_no', 25)->nullable()->collation('latin1_swedish_ci');
            $table->string('bill_date', 20)->nullable()->collation('latin1_swedish_ci');
            $table->string('payer', 20)->nullable()->collation('latin1_swedish_ci');
            $table->string('payer_name', 200)->nullable()->collation('latin1_swedish_ci');
            $table->string('status_m_faktur', 5)->nullable()->collation('latin1_swedish_ci');
            $table->string('npwp', 50)->nullable()->collation('latin1_swedish_ci');
            $table->char('status', 1)->default('t')->collation('latin1_swedish_ci');

            // Indexes
            $table->unique('id_m_faktur_detail');

            $table->index('bill_no', 'idx_faktur_detail_bill_no');
            $table->index('payer', 'idx_faktur_detail_payer');
            $table->index('id_m_loading', 'Idx_fak_ml');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m_faktur_detail');
    }
};
