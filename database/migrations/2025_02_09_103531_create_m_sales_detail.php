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
        Schema::create('m_sales_detail', function (Blueprint $table) {
            $table->increments('id_m_sales_detail'); // Auto-increment primary key
            $table->integer('id_m_loading')->nullable();
            $table->string('bill_no', 20)->nullable()->collation('latin1_swedish_ci');
            $table->string('kode', 15)->nullable()->collation('latin1_swedish_ci');
            $table->string('nama', 100)->nullable()->collation('latin1_swedish_ci');
            $table->string('material_number', 20)->nullable()->collation('latin1_swedish_ci');
            $table->string('currency', 5)->nullable()->collation('latin1_swedish_ci');
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->string('bill_type', 20)->nullable()->collation('latin1_swedish_ci');
            $table->decimal('qty_roll', 10, 2)->nullable();
            $table->decimal('qt_kg', 10, 2)->nullable();
            $table->string('uom', 15)->nullable()->collation('latin1_swedish_ci');
            $table->decimal('total_amount', 12, 2)->nullable();

            $table->unique('id_m_sales_detail', 'idx_id_m_sales_detail');

            $table->index('id_m_loading', 'idx_id_m_loading');
            $table->index('kode', 'idx_productcode_sales');
            $table->index('nama', 'idx_sales_detail_nama');
            $table->index('bill_no', 'idx_sales_bill');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_sales_detail');
    }
};
