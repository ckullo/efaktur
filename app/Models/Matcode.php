<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matcode extends Model
{
    use HasFactory;

    protected $table = 'm_matcode'; // Custom table name
    protected $primaryKey = 'id_m_matcode'; // Primary key

    public $timestamps = false; // Disable timestamps

    protected $fillable = [
        'id_m_matcode_file',
        'nama',
        'status',
        'description',
        'userid_created',
        'date_created',
        'userid_modified',
        'date_modified',
    ];

    protected $casts = [
        'date_created' => 'datetime',
        'date_modified' => 'datetime',
    ];

    public function matcodeFile()
    {
        return $this->belongsTo(MatcodeFile::class, 'id_m_matcode_file', 'id_m_matcode_file');
    }
}
