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

use App\Models\User;
use App\Models\Department;
use App\Filament\Resources\UserResource\Pages;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'User Management';

    protected static ?string $navigationGroup = 'Master';

    public static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                Textarea::make('keterangan'),
                Select::make('id_m_departemen')
                    ->label('Department')
                    ->options(Department::pluck('nama_departemen', 'id_m_departemen')->toArray())
                    ->searchable()
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        't' => 'Active',
                        'n' => 'Inactive',
                    ])
                    ->default('active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('No'),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->sortable(),
                TextColumn::make('departemen.nama_departemen')
                ->sortable(query: function ($query, $direction) {
                    return $query->orderBy(
                        Department::select('nama_departemen')
                            ->whereColumn('m_departemen.id_m_departemen', 'users.id_m_departemen'),
                        $direction
                    );
                })
                    ->label('Department'),
                TextColumn::make('keterangan')
                    ->label('Notes'),
                TextColumn::make('status')
                    ->sortable(),
                TextColumn::make('updated_by')
                    ->sortable()
                    ->label('Updated by')
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
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
