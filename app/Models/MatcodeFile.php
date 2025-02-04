<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatcodeFile extends Model
{
    use HasFactory;

    protected $table = 'm_matcode_file'; // Custom table name
    protected $primaryKey = 'id_m_matcode_file'; // Primary key

    public $timestamps = false; // Disable timestamps

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

    public function matcodes()
    {
        return $this->hasMany(Matcode::class, 'id_m_matcode_file', 'id_m_matcode_file');
    }

}
