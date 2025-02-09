<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FakturSales extends Model
{
    use HasFactory;

    protected $table = 'm_sales_detail';
    protected $primaryKey = 'id_m_sales_detail';

    public $timestamps = false;
}
