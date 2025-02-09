<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesDetail extends Model
{
    use HasFactory;

    protected $table = 'm_sales_detail';
    protected $primaryKey = 'id_m_sales_detail';

    public $timestamps = false;

    protected $fillable = [
        'id_m_loading',
        'bill_no',
        'kode',
        'nama',
        'material_number',
        'currency',
        'unit_price',
        'bill_type',
        'qty_roll',
        'qty_kg',
        'uom',
        'total_amount',
    ];

    public function loading()
    {
        return $this->belongsTo(Loading::class, 'id_m_loading', 'id_m_loading');
    }
}
