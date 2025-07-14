<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NcageApplicationResource\Pages;
use App\Models\NcageApplication;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NcageApplicationResource extends Resource
{
    protected static ?string $model = NcageApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Kosongkan form, karena tidak digunakan
    public static function form(Form $form): Form
    {
        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')->label('Nama Pemohon')->toggleable(),
                Tables\Columns\TextColumn::make('status_id')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'gray' => fn ($state) => $state === 'Draft',
                        'yellow' => fn ($state) => $state === 'Menunggu Verifikasi',
                        'green' => fn ($state) => $state === 'Verifikasi Berhasil',
                        'blue' => fn ($state) => $state === 'Menunggu Input Sertifikat',
                        'success' => fn ($state) => $state === 'Selesai',
                    ])
                    ->formatStateUsing(fn ($state, $record) => $record->getStatusLabel())
                    ->extraAttributes(['class' => 'px-4 py-2'])
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->label('Tanggal Pengajuan')
                    ->toggleable(),
            ])
            ->actions([
                Tables\Actions\Action::make('verifikasi')
                    ->label('Verifikasi')
                    ->button()
                    ->visible(fn ($record) => $record->status_id === 3)
                    ->url(fn ($record) => route('filament.admin.resources.ncage-applications.verify-request', ['record' => $record->id])),

                // Tables\Actions\Action::make('inputSertifikat')
                //     ->label('Input Sertifikat')
                //     ->visible(fn ($record) => $record->status_id === 4)
                //     ->url(fn ($record) => route('filament.admin.resources.ncage-applications.input-certificate', $record)),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNcageApplications::route('/'),
            'verify-request' => Pages\VerifyRequest::route('/{record}/verify-request'),
        ];
    }
}
