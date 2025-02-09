<?php

namespace App\Imports;

use App\Models\FakturDetail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class FakturImport implements ToCollection
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

            FakturDetail::create([
                'id_m_loading' => $this->id_m_loading,
                'faktur_no'=> $row[0],
                'bill_no'=> $row[1],
                'bill_date'=> $row[2],
                'payer'=> $row[3],
                'payer_name'=> $row[4],
                'npwp'=> $row[5],
                'status_m_faktur'=> $row[6],
            ]);
        }
    }
}
