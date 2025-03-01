<?php

namespace App\Filament\Resources\FakturSalesResource\Pages;

use App\Filament\Resources\FakturSalesResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ManageRecords;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use SimpleXMLElement;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Imports\FakturImport;
use App\Imports\SalesImport;
use App\Models\Loading;

use Carbon\Carbon;

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
                        ->preserveFilenames()
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                        ->required(),

                    FileUpload::make('sales_file')
                        ->label('Upload Sales File')
                        ->storeFiles()
                        ->disk('local')
                        ->directory('uploads/loading')
                        ->preserveFilenames()
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                        ->required(),
                ])
                ->action(function (array $data) {
                    // retrieve file path
                    $fakturFilePath = Storage::disk('local')->path($data['faktur_file']);
                    $salesFilePath = Storage::disk('local')->path($data['sales_file']);

                    // ensure faktur file exist in storage
                    if (!isset($data['faktur_file']) || !Storage::disk('local')->exists($data['faktur_file'])) {
                        throw new \Exception('Invalid file upload. Please try again.');
                    }

                    // ensure sales file exist in storage
                    if (!isset($data['sales_file']) || !Storage::disk('local')->exists($data['sales_file'])) {
                        throw new \Exception('Invalid file upload. Please try again.');
                    }

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
                        'no_' => $nextNo,
                        'nama_file_faktur' => $fakturFileName,
                        'nama_file_sales' => $salesFileName,
                        'periode' => $data['periode'],
                        'lokasi_file' => '../uploads/loading',
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

                    static::generateXML($loading->id_m_loading);

                    \Filament\Notifications\Notification::make()
                        ->title('XML File Generated')
                        ->success()
                        ->body("XML file generated successfully.")
                        ->send();
                }),


        ];
    }

    public static function generateXML($id_m_loading)
    {
        // ✅ 1. Fetch Header Data
        $headerData = DB::select("
            SELECT fd.bill_date AS tanggal_faktur,
                'Normal' AS jenis_faktur,
                '04' AS kode_transaksi,
                '' AS keterangan_tambahan,
                '' AS dokumen_pendukung,
                fd.bill_no AS referensi,
                '' AS cap_fasilitas,
                '0030794754415000000000' AS id_tku_penjual,
                IF(cd.id_type <> 'TIN', '0000000000000000', cd.npwp) AS npwp,
                cd.id_type,
                cd.kode_negara,
                IF(cd.id_type <> 'TIN', cd.nik, '-') AS nik ,
                cd.nama, cd.alamat,
                '' AS email,
                cd.id_tku AS id_tku_payer
            FROM m_faktur_detail AS fd
                INNER JOIN m_customer_detail AS cd ON fd.payer = cd.kode
            WHERE fd.id_m_loading = ?
            ORDER BY fd.bill_no
        ", [$id_m_loading]);

        // ✅ 2. Fetch Detail Data
        $detailData = DB::select("
            SELECT bill_no AS referensi,
                'A' AS Brg,
                '392100' AS KodeBrg,
                mc.description as nama,
                'UM.0003' AS satuan_ukur,
                sd.unit_price,
                sd.qty_kg,
                0 AS diskon,
                sd.total_amount AS dpp,
                ROUND((total_amount * 11/mv.nilai_vat),2) AS dpp_nilai_lain,
                mv.nilai_vat AS tarif_ppn,
		        ROUND((sd.total_amount * 11/mv.nilai_vat) * (mv.nilai_vat/100),2) AS ppn,
                0 AS tarif_ppnbm,
                0 AS ppnbm
            FROM m_sales_detail AS sd
            INNER JOIN m_matcode mc ON sd.material_number = mc.nama, m_vat mv
            WHERE sd.bill_no in (SELECT bill_no FROM m_faktur_detail WHERE id_m_loading = ?)
        ", [$id_m_loading]);

        // ✅ 3. Create XML Structure
        $xml = new SimpleXMLElement('<TaxInvoiceBulk/>');
        $xml->addAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xml->addAttribute('xsi:noNamespaceSchemaLocation', 'TaxInvoice.xsd');
        $xml->addChild('TIN', '0030794754415000');
        $listOfInvoices = $xml->addChild('ListOfTaxInvoice');

        foreach ($headerData as $header) {
            $invoice = $listOfInvoices->addChild('TaxInvoice');
            $invoice->addChild('TaxInvoiceDate', $header->tanggal_faktur);
            $invoice->addChild('TaxInvoiceOpt', $header->jenis_faktur);
            $invoice->addChild('TrxCode', $header->kode_transaksi);
            $invoice->addChild('AddInfo', $header->keterangan_tambahan);
            $invoice->addChild('CustomDoc', $header->dokumen_pendukung);
            $invoice->addChild('CustomDocMonthYear');
            $invoice->addChild('RefDesc', $header->referensi);
            $invoice->addChild('FacilityStamp', $header->cap_fasilitas);
            $invoice->addChild('SellerIDTKU', $header->id_tku_penjual);
            $invoice->addChild('BuyerTin', $header->npwp);
            $invoice->addChild('BuyerDocument', $header->id_type);
            $invoice->addChild('BuyerCountry', $header->kode_negara);
            $invoice->addChild('BuyerDocumentNumber',$header->nik);
            $invoice->addChild('BuyerName', $header->nama);
            $invoice->addChild('BuyerAdress', $header->alamat);
            $invoice->addChild('BuyerEmail', $header->email);
            $invoice->addChild('BuyerIDTKU', $header->id_tku_payer);

            $listOfGoods = $invoice->addChild('ListOfGoodService');
            foreach ($detailData as $detail) {
                if ($detail->referensi == $header->referensi) {
                    $good = $listOfGoods->addChild('GoodService');
                    $good->addChild('Opt', $detail->Brg);
                    $good->addChild('Code', $detail->KodeBrg);
                    $good->addChild('Name', $detail->nama);
                    $good->addChild('Unit', $detail->satuan_ukur);
                    $good->addChild('Price', $detail->unit_price);
                    $good->addChild('Qty', $detail->qty_kg);
                    $good->addChild('TotalDiscount', $detail->diskon);
                    $good->addChild('TaxBase', $detail->dpp);
                    $good->addChild('OtherTaxBase', $detail->dpp_nilai_lain);
                    $good->addChild('VATRate', $detail->tarif_ppn);
                    $good->addChild('VAT', $detail->ppn);
                    $good->addChild('STLGRate', $detail->tarif_ppnbm);
                    $good->addChild('STLG', $detail->ppnbm);
                }
            }
        }

        // ✅ 4. Save and Download the XML File
        $fileName = 'FakturKeluaran_' . $id_m_loading . '.xml';
        $filePath = 'exports/' . $fileName;

        Storage::put($filePath, $xml->asXML());

        Loading::where('id_m_loading', $id_m_loading)->update([
            'nama_file_xml' => $fileName
        ]);

        // return Response::download(storage_path('app/' . $filePath), $fileName, [
        //     'Content-Type' => 'application/xml',
        // ])->send();
    }

}
