<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $table = 'm_departemen'; // Custom table name

    protected $primaryKey = 'id_m_departemen'; // Primary key

    protected $fillable = [
        'nama_departemen',
        'status',
        'userid_created',
        'date_created',
        'userid_modified',
        'date_modified',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'id_m_departemen', 'id_m_departemen');
    }
}
