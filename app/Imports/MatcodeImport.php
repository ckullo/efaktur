<?php

namespace App\Imports;

use App\Models\Matcode;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class MatcodeImport implements ToCollection
{
    protected $matcodeFileId;

    public function __construct($matcodeFileId)
    {
        $this->matcodeFileId = $matcodeFileId;
    }

    public function collection(Collection $rows)
    {
        // Skip the first row (header)
        $rows->shift();

        foreach ($rows as $row) {
            Matcode::create([
                'nama' => $row[0], // Matcode
                'description' => $row[1], // Description
                'id_m_matcode_file' => $this->matcodeFileId,
            ]);
        }
    }
}
