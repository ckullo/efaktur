<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'm_customer'; // Custom table name
    protected $primaryKey = 'id_m_customer'; // Custom primary key
    public $timestamps = false; // Disable timestamps (since we have custom date fields)

    protected $fillable = [
        'nama_file',
        'status',
        'jumlah',
        'lokasi_file',
        'ukuran',
        'keterangan',
        'userid_created',
        'date_created',
        'userid_modified',
        'date_modified',
    ];

    protected $casts = [
        'date_created' => 'datetime',
        'date_modified' => 'datetime',
    ];
}
