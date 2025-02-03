<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPN extends Model
{
    use HasFactory;

    protected $table = 'm_vat'; // Define the correct table name

    protected $primaryKey = 'id_m_vat'; // Define the primary key

    public $timestamps = false; // Disable Laravel's default timestamps

    protected $fillable = [
        'nilai_vat',
        'status',
        'userid_created',
        'note',
        'date_created',
        'userid_modified',
        'date_modified',
    ];

    protected $casts = [
        'date_created' => 'datetime',
        'date_modified' => 'datetime',
    ];
}
