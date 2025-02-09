<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Loading extends Model
{
    use HasFactory;

    protected $table = 'm_loading';
    protected $primaryKey = 'id_m_loading';

    public $timestamps = false;

    protected $fillable = [
        'id_m_loading',
        'no_',
        'nama_file_faktur',
        'nama_file_sales',
        'nama_file_csv',
        'periode',
        'status',
        'jumlah',
        'lokasi_file',
        'ukuran',
        'keterangan',
        'userid_created',
        'date_created',
        'userid_modified',
        'date_modified',
        'date_approve',
    ];

    public static function generateNo($periode)
    {
        $lastRecord = self::where('periode', $periode)->orderBy('no_', 'desc')->first();

        return $lastRecord ? $lastRecord->no_ + 1 : 1;
    }

    public function salesDetails()
    {
        return $this->hasMany(SalesDetail::class, 'id_m_loading', 'id_m_loading');
    }
}
