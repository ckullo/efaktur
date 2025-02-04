<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MatcodeResource\Pages;
use App\Filament\Resources\MatcodeResource\RelationManagers;
use App\Models\Matcode;
use App\Models\MatcodeFile;
use App\Imports\MatcodeImport;

// use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MatcodeResource extends Resource
{
    protected static ?string $model = MatcodeFile::class;

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';

    protected static ?string $navigationLabel = 'Matcode';

    protected static ?string $navigationGroup = 'Master';

    public static ?int $navigationSort = 2;

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
                TextColumn::make('id_m_matcode_file')->label('No.'),
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
                // ]),
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
                return view('filament.modals.matcode', [
                    'matcode' => Matcode::where('id_m_matcode_file', $record->id_m_matcode_file ?? null)->get(),
                ]);
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMatcodes::route('/'),
        ];
    }

    
}
