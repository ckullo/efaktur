<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FakturSalesResource\Pages;
use App\Filament\Resources\FakturSalesResource\RelationManagers;
use App\Models\Loading;

use Filament\Forms;
use Filament\Forms\Components\Group as ComponentsGroup;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRecords;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Layout\Split;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FakturSalesResource extends Resource
{
    protected static ?string $model = Loading::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Faktur dan Sales';
    protected static ?string $navigationGroup = 'Transaksi';

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
            ->columns([
                TextColumn::make('nama_file_faktur')->label('File Faktur'),
                TextColumn::make('nama_file_sales')->label('File Sales'),
                TextColumn::make('salesDetails.currency')->label('Curr'),
                TextColumn::make('salesDetails.bill_date')->label('Bill Date'),
                TextColumn::make('count(salesDetails.customer')->label('Cust'),
                TextColumn::make('count(salesDetails.transaction_type')->label('Trans.'),
                TextColumn::make('salesDetails.total_amount')->label('Amount')->numeric(),
                TextColumn::make('keterangan')->label('Desc'),
                TextColumn::make('nama_file_csv')->label('CSV File'),
            ])
            ->filters([
                SelectFilter::make('periode')
                    ->options(fn() => [
                        Loading::query()
                            ->distinct()
                            ->pluck('periode', 'periode')

                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageFakturSales::route('/'),
        ];
    }
}
