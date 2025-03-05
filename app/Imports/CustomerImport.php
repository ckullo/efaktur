<?php

namespace App\Imports;

use App\Models\CustomerDetail;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CustomerImport implements ToCollection
{
    protected $customerId;

    public function __construct($customerId)
    {
        $this->customerId = $customerId;
    }

    public function collection(Collection $rows)
    {

        //Skip the first row (header)
        $rows->shift();

        foreach ($rows as $row) {
            $kode = $row[0];

            try{
                CustomerDetail::updateOrCreate(
                    ['kode' => $kode],
                    [
                        'nama' => $row[1],
                        'alamat' => $row[2],
                        'kota' => $row[3],
                        'kode_pos' => $row[4],
                        'npwp' => $row[5],
                        'id_tku' => $row[6],
                        'nik' => $row[7],
                        'id_type' => $row[8],
                        'kode_negara' => $row[9],
                    ]
                );
            } Catch(QueryException $e){
                if($e->getCode() == '23000'){
                    $existingCustomer = CustomerDetail::where('kode', $kode)->first();

                    if($existingCustomer){
                        $existingCustomer->update([
                            'id_m_customer' => $this->customerId,
                            'nama' => $row[1],
                            'alamat' => $row[2],
                            'kota' => $row[3],
                            'kode_pos' => $row[4],
                            'npwp' => $row[5],
                            'id_tku' => $row[6],
                            'nik' => $row[7],
                            'id_type' => $row[8],
                            'kode_negara' => $row[9],
                        ]);
                    }
                } else {
                    throw $e;
                }
            }

        }
    }
}
