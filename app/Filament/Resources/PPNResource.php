<?php

namespace App\Filament\Resources;

use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Password;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

use App\Filament\Resources\PPNResource\Pages;
use App\Filament\Resources\PPNResource\RelationManagers;
use App\Models\PPN;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PPNResource extends Resource
{
    protected static ?string $model = PPN::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'PPN';

    protected static ?string $navigationGroup = 'Master';

    protected static ?string $slug = 'ppn';

    public static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nilai_vat')
                    ->required(),
                Textarea::make('note'),
                Select::make('status')
                    ->options([
                        't' => 'Active',
                        'f' => 'Inactive',
                    ])
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_m_vat')->label('No.'),
                TextColumn::make('nilai_vat')->label('PPN(%)'),
                TextColumn::make('note')->label('Note'),
                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => $state === 't' ? 'Active' : 'Inactive')
                    ->sortable(),
                TextColumn::make('userid_modified')->label('Modified By'),
                TextColumn::make('date_modified')->label('Last Update'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePPNS::route('/'),
        ];
    }
}
