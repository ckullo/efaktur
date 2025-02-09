<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faktur extends Model
{
    use HasFactory;

    protected $table = 'm_faktur';
    protected $primaryKey = 'id_m_faktur';

    public $timestamps = false;

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

}
