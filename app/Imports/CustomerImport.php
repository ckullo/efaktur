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
                'alamat' => $row[6] . ', ' . $row[7], // Street + Street 2
                'kota' => $row[3], // City
            ]);
        }
    }
}
