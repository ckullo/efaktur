<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FakturDetail extends Model
{
    use HasFactory;

    protected $table = 'm_faktur_detail';
    protected $primaryKey = 'id_m_faktur_detail';

    public $timestamps = false;

    protected $fillable = [
        'id_m_loading',
        'faktur_no',
        'bill_no',
        'bill_date',
        'payer',
        'payer_name',
        'status_m_faktur',
        'npwp',
        'status',
    ];
}
