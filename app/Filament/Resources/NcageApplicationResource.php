<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NcageApplicationResource\Pages;
use App\Models\NcageApplication;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use App\Models\Admin;

class NcageApplicationResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = NcageApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Data Permohonan NCAGE';

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'verify',
            'validate',
        ];
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        $permissions = [
            'view_any_ncage::application',
            'verify_ncage::application',
            'validate_ncage::application',
        ];

        return collect($permissions)->contains(fn ($permission) => $user->can($permission));
    }

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Status Permohonan')
                ->schema([
                    Placeholder::make('user_name')
                        ->label('Nama Pemohon')
                        ->content(fn ($record) => $record?->user?->name ?? '-')
                        ->columnSpan(2),
        
                    TextInput::make('status_id')
                        ->label('Status')
                        ->default(fn ($record) => $record?->getStatusLabel())
                        ->disabled()
                        ->columnSpan(1),
                ])->columns(3),
            
            Forms\Components\Section::make('Status Permohonan')
                ->schema([
                    DatePicker::make('created_at')
                    ->label('Tanggal Pengajuan')
                    ->disabled()
                    ->columnSpan(1),

                Placeholder::make('application_type')
                    ->label('Jenis Permohonan')
                    ->content(fn ($record) => $record?->identity?->getApplicationTypeLabelAttribute())
                    ->columnSpan(1),
                
                Placeholder::make('ncage_request_type')
                    ->label('Jenis Permohonan NCAGE')
                    ->content(fn ($record) => $record?->identity?->getNcageRequestTypeLabelAttribute())
                    ->columnSpan(1),
                ])->columns(3),
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
                Tables\Columns\TextColumn::make('verified_by')
                    ->label('Diverifikasi Oleh')
                    ->formatStateUsing(function ($state) {
                        return Admin::find($state)?->name ?? '-';
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('validated_by')
                    ->label('Divalidasi Oleh')
                    ->formatStateUsing(function ($state) {
                        return Admin::find($state)?->name ?? '-';
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('revision_by')
                    ->label('Diminta Revisi Oleh')
                    ->formatStateUsing(function ($state) {
                        return Admin::find($state)?->name ?? '-';
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rejected_by')
                    ->label('Ditolak Oleh')
                    ->formatStateUsing(function ($state) {
                        return Admin::find($state)?->name ?? '-';
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordUrl(fn ($record) => route('filament.admin.resources.ncage-applications.view', ['record' => $record->id]))
            ->actions([
                // Tombol ini akan mengarahkan admin ke halaman verifikasi kustom Anda
                Tables\Actions\Action::make('verify')
                    ->label('Lihat & Verifikasi')
                    ->icon('heroicon-o-document-magnifying-glass')
                    // Tampilkan tombol ini untuk status yang relevan
                    ->visible(function ($record) {
                        return in_array($record->status_id, [1, 2])
                            && auth()->user()->can('verify_ncage::application');
                    })
                    ->url(fn ($record) => route('filament.admin.resources.ncage-applications.verify-request', ['record' => $record->id])),

                // Tombol ini akan mengarahkan admin ke halaman validasi kustom Anda
                Tables\Actions\Action::make('validate')
                    ->label('Lihat & Validasi')
                    ->icon('heroicon-o-check-badge')
                     // Tampilkan tombol ini hanya saat statusnya 'Proses Validasi'
                    ->visible(function ($record) {
                        return $record->status_id === 4
                            && auth()->user()->can('validate_ncage::application');
                    })
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