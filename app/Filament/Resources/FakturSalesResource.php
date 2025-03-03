<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FakturSalesResource\Pages;
use App\Models\Loading;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\Layout\Layout;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use SimpleXMLElement;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FakturSalesResource extends Resource
{
    protected static ?string $model = Loading::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Faktur dan Sales';
    protected static ?string $navigationGroup = 'Transaksi';
    public static ?int $navigationSort = 5;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->query(static::getCustomQuery())
            ->columns([
                TextColumn::make('row_number')
                ->label('No.')
                ->getStateUsing(function ($rowLoop, $record) {
                    return $rowLoop->iteration;
                }),

                TextColumn::make('nama_file_faktur')
                    ->label('File Faktur'),

                TextColumn::make('nama_file_sales')
                    ->label('File Sales'),

                ColumnGroup::make('Summary')
                    ->columns([ // ✅ Correct Grouping
                        TextColumn::make('currency')
                            ->label('Curr.')
                            ->alignCenter(),

                        TextColumn::make('bill_date')
                            ->label('Bill Date')
                            ->alignCenter(),

                        TextColumn::make('cust')
                            ->label('Cust')
                            ->numeric()
                            ->alignRight(),

                        TextColumn::make('trans')
                            ->label('Trans.')
                            ->numeric()
                            ->alignRight(),

                        TextColumn::make('amount')
                            ->label('Amount')
                            ->numeric()
                            ->alignRight(),
                    ])
                    ->alignment(Alignment::Center)
                    ->wrapHeader(),

                TextColumn::make('nama_file_xml')
                    ->label('File XML'),

                TextColumn::make('nama_file_xls')
                    ->label('File XLS'),

                TextColumn::make('keterangan')
                    ->label('Keterangan'),
            ])
            ->filters([
                // SelectFilter::make('periode')
                //     ->options(fn() => [
                //         Loading::query()
                //             ->distinct()
                //             ->pluck('periode', 'periode')

                //     ]),

            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                Action::make('Export to Excel')
                    ->label('Generate Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->requiresConfirmation()
                    ->modalHeading('Generate Excel')
                    // ->modalSubheading('Do you want to generate the Excel file for this faktur?')
                    ->modalSubmitActionLabel('Generate')
                    ->action(function ($record) {
                        $id_m_loading = $record->id_m_loading;

                        $headers = DB::select("
                        SELECT fd.bill_date AS tanggal_faktur,
                                'Normal' AS jenis_faktur,
                                '04' AS kode_transaksi,
                                '' AS keterangan_tambahan,
                                '' AS dokumen_pendukung,
                                '' AS period_dok_pendukung,
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
                            ORDER BY fd.bill_no asc
                    ", [$id_m_loading]);

                        // ✅ 2. Fetch Detail Data
                        $details = DB::select("
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

                        // ✅ 3. Create Excel File with Multiple Sheets
                        $spreadsheet = new Spreadsheet();

                        // ✅ Add Header Data to "Faktur" Sheet
                        $fakturSheet = $spreadsheet->getActiveSheet();
                        $fakturSheet->setTitle('Faktur');
                        $fakturSheet->fromArray(array_keys((array) $headers[0]), NULL, 'A1'); // Headers
                        $fakturSheet->fromArray(array_map('get_object_vars', $headers), NULL, 'A2'); // Data

                        // ✅ Add Detail Data to "DetailFaktur" Sheet
                        $detailSheet = $spreadsheet->createSheet();
                        $detailSheet->setTitle('DetailFaktur');
                        $detailSheet->fromArray(array_keys((array) $details[0]), NULL, 'A1'); // Headers
                        $detailSheet->fromArray(array_map('get_object_vars', $details), NULL, 'A2'); // Data

                        // ✅ Save the Excel File
                        $exportPath = storage_path('app/private/exports');
                        $fileName = 'FakturKeluaran_'.$id_m_loading.'.xlsx';
                        $filePath =  $exportPath . '/' . $fileName;

                        $writer = new Xlsx($spreadsheet);
                        $writer->save($filePath);

                        Loading::where('id_m_loading', $id_m_loading)->update([
                            'nama_file_xls' => $fileName
                        ]);
                    })
                    ,

            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageFakturSales::route('/'),
        ];
    }

    protected static function getCustomQuery(): Builder
    {
        return Loading::query()
            ->leftJoin('m_sales_detail', 'm_sales_detail.id_m_loading', '=', 'm_loading.id_m_loading')
            ->leftJoin('m_faktur_detail', 'm_sales_detail.bill_no', '=', 'm_faktur_detail.bill_no')
            ->selectRaw("
                m_loading.id_m_loading,
                m_loading.nama_file_faktur,
                m_loading.nama_file_sales,
                m_faktur_detail.bill_date,
                COUNT(DISTINCT m_faktur_detail.payer) AS cust,
                COUNT(*) AS trans,
                m_sales_detail.currency,
                SUM(m_sales_detail.total_amount) AS amount,
                IFNULL(m_loading.nama_file_xml,'') AS nama_file_xml,
                IFNULL(m_loading.nama_file_xls,'') AS nama_file_xls,
                IFNULL(m_loading.keterangan,'') AS keterangan
            ")
            ->where('m_faktur_detail.bill_date', '<>', '')
            ->groupBy(
                'm_loading.id_m_loading',
                'm_loading.nama_file_faktur',
                'm_loading.nama_file_sales',
                'm_faktur_detail.bill_date',
                'm_sales_detail.currency',
                'm_loading.nama_file_xml',
                'm_loading.nama_file_xls',
                'm_loading.keterangan'
            );
    } // ✅ Convert the query result to a collection


}
