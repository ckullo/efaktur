<?php

namespace App\Imports;

use App\Models\SalesDetail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SalesImport implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    protected $id_m_loading;

    public function __construct($id_m_loading)
    {
        $this->id_m_loading = $id_m_loading;
    }

    public function collection(Collection $collection)
    {
        $collection->shift();

        foreach ($collection as $row) {
            if ($row[0] == null) {
                break;
            }

            SalesDetail::create([
                'id_m_loading' => $this->id_m_loading,
                'bill_no'=> $row[0],
                'kode'=> $row[1],
                'nama'=> $row[2],
                'material_number'=> $row[3],
                'currency'=> $row[4],
                'unit_price'=> floatval(str_replace(',','',$row[5])),
                'bill_type'=> $row[6],
                'qty_roll'=> floatval(str_replace(',','',$row[7])),
                'qty_kg'=> floatval(str_replace(',','',$row[8])),
                'uom'=> $row[9],
                'total_amount'=> floatval(str_replace(',','',$row[10])),
            ]);
        }
    }
}
