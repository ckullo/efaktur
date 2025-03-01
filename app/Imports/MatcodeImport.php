<?php

namespace App\Imports;

use App\Models\Matcode;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Database\QueryException;

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
            $nama = $row[0];
            $description = $row[1];

            try {
                // ✅ Handle updates or insertions with primary and unique constraints
                Matcode::updateOrCreate(
                    ['nama' => $nama], // Primary key for update
                    [
                        'description' => $description, // Unique column
                        'id_m_matcode_file' => $this->matcodeFileId,
                    ]
                );
            } catch (QueryException $e) {
                // ✅ Handle unique constraint violation on 'description'
                if ($e->getCode() == '23000') { // SQLSTATE 23000: Integrity constraint violation
                    // Attempt to find an existing record by 'description'
                    $existingMatcode = Matcode::where('description', $description)->first();

                    if ($existingMatcode) {
                        $existingMatcode->update([
                            'nama' => $nama, // Update primary key if needed
                            'id_m_matcode_file' => $this->matcodeFileId,
                        ]);
                    }
                } else {
                    // Re-throw exception if not a unique constraint violation
                    throw $e;
                }
            }
        }
    }
}
