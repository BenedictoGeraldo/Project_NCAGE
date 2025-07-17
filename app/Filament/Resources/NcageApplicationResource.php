<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NcageApplicationResource\Pages;
use App\Models\NcageApplication;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class NcageApplicationResource extends Resource
{
    protected static ?string $model = NcageApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Data Permohonan NCAGE';

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
                    ->formatStateUsing(fn ($record) => $record->getStatusLabel())
                    ->color(fn ($record) => match ($record->status_id) {
                        1 => 'info',      // Permohonan Dikirim (bg-blue-500)
                        2 => 'warning',   // Verifikasi Berkas & Data (bg-yellow-500)
                        3 => 'warning',   // Butuh Perbaikan (bg-yellow-500)
                        4 => 'primary',   // Proses Validasi (bg-blue-600)
                        5 => 'success',   // Sertifikat Diterbitkan (bg-green-500)
                        6 => 'danger',    // Permohonan Ditolak (bg-red-500)
                        default => 'gray', // Unknown status (bg-gray-500)
                    })
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
                    ->visible(fn ($record) => $record->status_id === 2)
                    ->url(fn ($record) => route('filament.admin.resources.ncage-applications.verify-request', ['record' => $record->id])),

                Tables\Actions\Action::make('validasi')
                    ->label('Validasi')
                    ->button()
                    ->visible(fn ($record) => $record->status_id === 4)
                    ->url(fn ($record) => route('filament.admin.resources.ncage-applications.validate-request', ['record' => $record->id])),
            ]);
    }

    /**
     * ✅ Override query untuk eager loading relasi `user`
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('user'); // Hindari N+1
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
        ];
    }
    public static function getRecordAction(NcageApplication $record): array
{
    return [
        Action::make('approve')
            ->label('Setujui Verifikasi')
            ->action(fn () => $record->update(['status' => 4]))
            ->requiresConfirmation()
            ->color('success'),

        Action::make('requestRevision')
            ->label('Minta Revisi')
            ->action(fn () => $record->update(['status' => 3]))
            ->requiresConfirmation()
            ->color('warning'),

        Action::make('reject')
            ->label('Tolak Permohonan')
            ->action(fn () => $record->update(['status' => 6]))
            ->requiresConfirmation()
            ->color('danger'),
    ];
}
}
