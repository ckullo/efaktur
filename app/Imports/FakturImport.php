<?php

namespace App\Imports;

use App\Models\FakturDetail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date;

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
                'bill_date'=> $this->convertExcelDateToString($row[2]),
                'payer'=> $row[3],
                'payer_name'=> $row[4],
                'npwp'=> $row[5],
                'status_m_faktur'=> $row[6],
            ]);
        }
    }

    private function convertExcelDateToString($excelDate)
    {
        if (is_numeric($excelDate)) {
            // ✅ Converts numeric Excel dates
            return Date::excelToDateTimeObject($excelDate)->format('Y-m-d');
        }

        // If the date is already in `d/m/Y` or similar format, convert it
        $dateTime = \DateTime::createFromFormat('Y/m/d', $excelDate);
        if ($dateTime) {
            return $dateTime->format('Y-m-d');
        }

        // If the format is unknown, return as is (fallback)
        return (string) $excelDate;

    }
}
