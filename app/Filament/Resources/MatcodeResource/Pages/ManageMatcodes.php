<?php

namespace App\Filament\Resources\MatcodeResource\Pages;

use App\Filament\Resources\MatcodeResource;
use App\Imports\MatcodeImport;

use Maatwebsite\Excel\Facades\Excel;

use Filament\Actions\Action;
use Filament\Resources\Pages\ManageRecords;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Livewire\TemporaryUploadedFile;

class ManageMatcodes extends ManageRecords
{
    protected static string $resource = MatcodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('import')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-on-square')
                ->modalHeading('Upload Excel File')
                ->modalDescription('Upload an Excel file to import Matcode data.')
                ->modalSubmitActionLabel('Import')
                ->form([
                    FileUpload::make('file')
                        ->label('Upload Excel File')
                        ->storeFiles()
                        ->disk('local')
                        ->directory('uploads/matcode')
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                        ->required(),
                    Textarea::make('keterangan')
                        ->label('Keterangan')
                        ->rows(3)
                        ->maxLength(500),
                ])
                ->action(function (array $data) {
                    $filePath = Storage::disk('local')->path($data['file']);

                    // Ensure the file exists in storage
                    if (!isset($data['file']) || !Storage::disk('local')->exists($data['file'])) {
                        throw new \Exception('Invalid file upload. Please try again.');
                    }
                    
                    // Retrieve the file from storage
                    $storedFileName = basename($filePath);
                    $fileSize = Storage::disk('local')->size($data['file']);
                    $keterangan = $data['keterangan'];
                        
                    // Count rows from Excel file
                    $excelData = Excel::toArray([], $filePath);
                    $rowCount = count($excelData[0]) - 1; // Exclude header row

                    // Save file info in `m_matcode_file`
                    $matcodeFile = \App\Models\MatcodeFile::create([
                        'nama_file' => $storedFileName,
                        'jumlah' => $rowCount,
                        'lokasi_file' => $filePath,
                        'ukuran' => $fileSize,
                        'keterangan' => $keterangan,
                    ]);

                    // Import data into `m_matcode`
                    Excel::import(new MatcodeImport($matcodeFile->id_m_matcode_file), $filePath);

                    \Filament\Notifications\Notification::make()
                        ->title('Import Successful')
                        ->success()
                        ->body("$rowCount records imported successfully.")
                        ->send();
                }),
        ];
    }
}
