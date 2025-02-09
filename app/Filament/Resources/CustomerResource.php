<?php

namespace App\Filament\Resources;

use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Password;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\Action;

use livewire\TemporaryUploadedFile;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use illuminate\storage\Facades\Storage;
use App\Models\Customer;
use App\Models\CustomerDetail;
use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Imports\CustomerImportManager;

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
                    ->sortable(),
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
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                static::showDetailsAction(),
                Action::make('download')
                ->label('')
                ->icon('heroicon-o-document-arrow-down')
                // ->color('success')
                ->url(fn ($record) => route('download.customerFile', ['filePath' => $record->lokasi_file]))
                ->openUrlInNewTab(), // ✅ Opens in new tab
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    protected static function showDetailsAction($record = null): Action
    {
        return Action::make('showDetails')
            ->label('')
            ->icon('heroicon-o-eye')
            // ->modalHeading('Customer Details')
            ->modalWidth('6xl')
            ->modalSubmitActionLabel('Close')
            ->modalContent(fn($record) => static::getModalContent($record));
    }

    protected static function getModalContent($record)
    {
        // ✅ Ensure `$record` exists before querying
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
