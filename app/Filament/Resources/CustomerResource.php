<?php

namespace App\Filament\Resources;

use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use App\Models\Customer;
use App\Filament\Resources\CustomerResource\Pages;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'Customer';
    protected static ?string $navigationGroup = 'Master';
    public static ?int $navigationSort = 3;

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
                TextColumn::make('id_m_customer')->label('No.'),
                TextColumn::make('nama_file')
                    ->label('Nama File')
                    ,
                TextColumn::make('jumlah')
                    ->label('Jumlah Record')
                    ->sortable(),
                TextColumn::make('keterangan')->label('Keterangan'),
                TextColumn::make('userid_modified')->label('User'),
                TextColumn::make('date_modified')->label('Waktu')
            ])
            ->filters([
                //
            ])
            ->actions([
                // show details
                static::showDetailsAction(),
                // download file
                Action::make('download')
                    ->label('')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn ($record) => route('download.customerFile', ['filePath' => $record->lokasi_file]))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([

            ]);
    }

    protected static function showDetailsAction($record = null): Action
    {
        return Action::make('showDetails')
            ->label('')
            ->icon('heroicon-o-eye')
            ->modalWidth('6xl')
            ->modalSubmitActionLabel('Close')
            ->modalContent(fn($record) => static::getModalContent($record));
    }

    protected static function getModalContent($record)
    {
        // Ensure `$record` exists before querying
        if (!$record) {
            return '<p class="text-gray-500">No details available.</p>';
        }

        return view('filament.modals.customer-details', [
            'id_m_customer' => $record->id_m_customer,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCustomers::route('/'),
        ];
    }
}
