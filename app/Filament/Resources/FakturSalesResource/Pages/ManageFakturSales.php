<?php

namespace App\Filament\Resources\FakturSalesResource\Pages;

use App\Filament\Resources\FakturSalesResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ManageRecords;
use Filament\Forms\Components\FileUpload;

use App\Filament\Resources\LoadingResource;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FakturImport;
use App\Imports\SalesImport;
use App\Models\Loading;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ManageFakturSales extends ManageRecords
{
    protected static string $resource = FakturSalesResource::class;

    public function getTitle(): string
    {
        return 'Faktur and Sales';
    }

    public function getHeading(): string
    {
        return 'Faktur and Sales';
    }

    protected function getHeaderActions(): array
    {
        return [

            Action::make('import')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-on-square')
                ->modalHeading('Upload Excel File')
                ->modalSubmitActionLabel('Import')
                ->form([
                    Select::make('periode')
                        ->label('Periode')
                        ->options([
                            Carbon::now()->subMonth()->format('mY') => 'Previous Month (' . Carbon::now()->subMonth()->format('m/Y') . ')',
                            Carbon::now()->format('mY') => 'Current Month (' . Carbon::now()->format('m/Y') . ')',
                            Carbon::now()->addMonth()->format('mY') => 'Next Month (' . Carbon::now()->addMonth()->format('m/Y') . ')',
                        ])
                        ->required(),

                    FileUpload::make('faktur_file')
                        ->label('Upload Faktur File')
                        ->storeFiles()
                        ->disk('local')
                        ->directory('uploads/loading')
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                        ->required(),

                    FileUpload::make('sales_file')
                        ->label('Upload Sales File')
                        ->storeFiles()
                        ->disk('local')
                        ->directory('uploads/loading')
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                        ->required(),
                ])
                ->action(function (array $data) {
                    // ensure faktur file exist in storage
                    if (!isset($data['faktur_file']) || !Storage::disk('local')->exists($data['faktur_file'])) {
                        throw new \Exception('Invalid file upload. Please try again.');
                    }

                    // ensure sales file exist in storage
                    if (!isset($data['sales_file']) || !Storage::disk('local')->exists($data['sales_file'])) {
                        throw new \Exception('Invalid file upload. Please try again.');
                    }

                    // retrieve file path
                    $fakturFilePath = Storage::disk('local')->path($data['faktur_file']);
                    $salesFilePath = Storage::disk('local')->path($data['sales_file']);

                    $fakturFileName = basename($fakturFilePath);
                    $salesFileName = basename($salesFilePath);

                    $sizeFaktur = Storage::disk('local')->size($data['faktur_file']);
                    $sizeSales = Storage::disk('local')->size($data['sales_file']);
                    $fileSize = $sizeFaktur + $sizeSales;

                    $fakturData = Excel::toArray([], $fakturFilePath);
                    $fakturCount = count($fakturData[0]) - 1;

                    $salesData = Excel::toArray([], $salesFilePath);
                    $salesCount = count($salesData[0]) - 1;

                    $nextNo = Loading::generateNo($data['periode']);

                    // insert into m_loading
                    $loading = Loading::create([
                        'no_'=> $nextNo,
                        'nama_file_faktur' => $fakturFileName,
                        'nama_file_sales' => $salesFileName,
                        'periode' => $data['periode'],
                        'lokasi_file'=> '../uploads/loading',
                        'ukuran' => $fileSize,
                        'userid_created' => Auth::id(),
                        'date_created' => Carbon::now(),

                    ]);

                    // Process excel file and insert into m_faktur_detail and m_sales_detail
                    Excel::import(new FakturImport($loading->id_m_loading), $fakturFilePath);
                    Excel::import(new SalesImport($loading->id_m_loading), $salesFilePath);

                    \Filament\Notifications\Notification::make()
                        ->title('Import Successful')
                        ->success()
                        ->body("$fakturCount Faktur records and $salesCount Sales records imported successfully.")
                        ->send();

                }),
        ];
    }
}
