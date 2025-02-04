<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Imports\CustomerImport;

use Maatwebsite\Excel\Facades\Excel;

use Filament\Actions\Action;
use Filament\Resources\Pages\ManageRecords;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;

use Illuminate\Support\Facades\Storage;

use Livewire\TemporaryUploadedFile;

class ManageCustomers extends ManageRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('import')
            ->label('Import Excel')
            ->icon('heroicon-o-arrow-up-on-square')
            ->modalHeading('Upload Excel File')
            ->modalDescription('Select an Excel file to import customer data.')
            ->modalButton('Import')
            ->form([
                FileUpload::make('file')
                    ->label('Upload Excel File')
                    ->storeFiles()
                    ->disk('local')
                    ->directory('uploads/customer')
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
                // $originalFileName =  $data['file'];
                $fileSize = Storage::disk('local')->size($data['file']);
                $keterangan = $data['keterangan'];

                // Count rows from Excel file
                $excelData = Excel::toArray([], $filePath);
                $rowCount = count($excelData[0]) - 1; // Exclude header row

                // Insert into `m_customer`
                $customer = \App\Models\Customer::create([
                    'nama_file' => $storedFileName,
                    'jumlah' => $rowCount,
                    'lokasi_file' => $filePath,
                    'ukuran' => $fileSize,
                    'keterangan' => $keterangan,
                ]);

                // Process Excel file and insert into `m_customer_detail`
                Excel::import(new CustomerImport($customer->id_m_customer),$filePath);
                
                // Delete the uploaded file from storage
                // Storage::disk('local')->delete($data['file']);

                \Filament\Notifications\Notification::make()
                    ->title('Import Successful')
                    ->success()
                    ->body("$rowCount records imported successfully.")
                    ->send();
            }),
        ];
    }
}
