<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matcode extends Model
{
    use HasFactory;

    protected $table = 'm_code_mat'; // Set the custom table name
    protected $primaryKey = 'id_m_code_mat'; // Set the primary key

    public $timestamps = false; // Disable default timestamps

    protected $fillable = [
        'nama_code_mat',
        'status',
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
