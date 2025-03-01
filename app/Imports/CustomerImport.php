<?php

namespace App\Imports;

use App\Models\CustomerDetail;
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
        // Skip the first row (header)
        $rows->shift();

        foreach ($rows as $row) {
            CustomerDetail::create([
                'id_m_customer' => $this->customerId,
                'kode' => $row[0], // Cust. Number
                'nama' => $row[1], // Name
                'alamat' => $row[2], // Address
                'kota' => $row[3], // City
                'kode_pos' => $row[4], // Postal Code
                'npwp' => $row[5], // NPWP
                'id_tku' => $row[6], // TKU
                'nik' => $row[7], // NIK
                'id_type' => $row[8], // Type
                'kode_negara' => $row[9], // Country Code
            ]);
        }
    }
}
