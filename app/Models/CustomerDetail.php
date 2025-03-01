<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDetail extends Model
{
    use HasFactory;

    protected $table = 'm_customer_detail'; // Define the custom table name
    protected $primaryKey = 'id_m_customer_detail'; // Set primary key

    public $timestamps = false;

    protected $fillable = [
        'id_m_customer',
        'kode',
        'nama',
        'alamat',
        'kota',
        'kode_pos',
        'kode_negara',
        'npwp',
        'id_tku',
        'nik',
        'id_type',
        'status'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_m_customer', 'id_m_customer');
    }
}
