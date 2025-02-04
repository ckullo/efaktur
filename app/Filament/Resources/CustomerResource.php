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
                    ->sortable()
                    ->searchable()
                    ->action(fn($record) => static::showDetailsAction($record))
                    ->color('primary')
                    ->extraAttributes(['class' => 'cursor-pointer']),
                TextColumn::make('jumlah')->label('Jumlah Record'),
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
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    protected static function showDetailsAction($record = null): Action
    {
        return Action::make('showDetails')
            ->label('View Details')
            ->icon('heroicon-o-eye')
            ->modalHeading('Customer Details')
            ->modalWidth('2xl')
            ->modalSubmitActionLabel('Close')
            ->modalContent(function () use ($record) {
                return view('filament.modals.customer-details', [
                    'customerDetails' => CustomerDetail::where('id_m_customer', $record->id_m_customer ?? null)->get(),
                ]);
            });
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCustomers::route('/'),
        ];
    }
}
