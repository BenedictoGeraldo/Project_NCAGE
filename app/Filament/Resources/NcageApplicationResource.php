<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NcageApplicationResource\Pages;
use App\Models\NcageApplication;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

class NcageApplicationResource extends Resource
{
    protected static ?string $model = NcageApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Data Permohonan NCAGE';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Placeholder::make('user_name')
                ->label('Nama Pemohon')
                ->content(fn ($record) => $record?->user?->name ?? '-'),

            TextInput::make('status_id')
                ->label('Status')
                ->default(fn ($record) => $record?->getStatusLabel())
                ->disabled(),

            DatePicker::make('created_at')
                ->label('Tanggal Pengajuan')
                ->disabled(),

            Placeholder::make('application_type')
                ->label('Jenis Permohonan')
                ->content(fn ($record) => $record?->identity?->getApplicationTypeLabel()),
            
            Placeholder::make('ncage_request_type')
                ->label('Jenis Permohonan NCAGE')
                ->content(fn ($record) => $record?->identity?->getNcageRequestTypeLabel()),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')->label('Nama Pemohon')->toggleable(),
                Tables\Columns\TextColumn::make('companyDetail.name')->label('Nama Perusahaan')->toggleable(),
                Tables\Columns\TextColumn::make('status_id')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($record) => $record->getStatusLabel())
                    ->color(fn ($record) => match ($record->status_id) {
                        1 => 'info',      // Permohonan Dikirim
                        2, 3 => 'warning', // Verifikasi / Butuh Perbaikan
                        4 => 'primary',   // Proses Validasi
                        5 => 'success',   // Sertifikat Diterbitkan
                        6 => 'danger',    // Permohonan Ditolak
                        default => 'gray',
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->label('Tanggal Pengajuan')
                    ->toggleable(),
            ])
            ->recordUrl(fn ($record) => route('filament.admin.resources.ncage-applications.view', ['record' => $record->id]))
            ->actions([
                // Tombol ini akan mengarahkan admin ke halaman verifikasi kustom Anda
                Tables\Actions\Action::make('verify')
                    ->label('Lihat & Verifikasi')
                    ->icon('heroicon-o-document-magnifying-glass')
                    // Tampilkan tombol ini untuk status yang relevan
                    ->visible(fn ($record) => in_array($record->status_id, [1, 2, 3]))
                    ->url(fn ($record) => route('filament.admin.resources.ncage-applications.verify-request', ['record' => $record->id])),

                // Tombol ini akan mengarahkan admin ke halaman validasi kustom Anda
                Tables\Actions\Action::make('validate')
                    ->label('Lihat & Validasi')
                    ->icon('heroicon-o-check-badge')
                     // Tampilkan tombol ini hanya saat statusnya 'Proses Validasi'
                    ->visible(fn ($record) => $record->status_id === 4)
                    ->url(fn ($record) => route('filament.admin.resources.ncage-applications.validate-request', ['record' => $record->id])),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('user'); // Eager load relasi user
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
            'validate-request' => Pages\ValidateRequest::route('/{record}/validate-request'),
            'view' => Pages\ViewNcageApplication::route('/{record}'),
        ];
    }
}