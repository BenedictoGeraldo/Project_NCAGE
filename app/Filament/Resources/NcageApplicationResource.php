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
use Filament\Forms\Components\View;
use App\Models\Admin;
use Twilio\TwiML\Voice\Play;

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
            
            Forms\Components\Section::make('Dokumen Terlampir')
                ->schema([
                    View::make('filament.components.ncage-documents')
                        ->columnSpan(3),
                ])->columns(3),

            Forms\Components\Section::make('A. Identifikasi Entitas')
                ->schema([
                    DatePicker::make('created_at')
                    ->label('Tanggal Pengajuan')
                    ->disabled()
                    ->columnSpan(2),

                    Placeholder::make('application_type')
                        ->label('Jenis Permohonan')
                        ->content(fn ($record) => $record?->identity?->getApplicationTypeLabelAttribute())
                        ->columnSpan(1),
                    
                    Placeholder::make('ncage_request_type')
                        ->label('Jenis Permohonan NCAGE')
                        ->content(fn ($record) => $record?->identity?->getNcageRequestTypeLabelAttribute())
                        ->columnSpan(1),
                    
                    Placeholder::make('purpose')
                        ->label('Tujuan')
                        ->content(fn ($record) => $record?->identity?->getPurposeLabelAttribute())
                        ->columnSpan(1),
                    
                    Placeholder::make('entity_type')
                        ->label('Tipe Entitas')
                        ->content(fn ($record) => $record?->identity?->getEntityTypeLabelAttribute())
                        ->columnSpan(1),
                    
                    Placeholder::make('building_ownership_status')
                        ->label('Status Pemilik Bangunan')
                        ->content(fn ($record) => $record?->identity?->getBuildingOwnershipStatusLabelAttribute())
                        ->columnSpan(1),
                    
                    Placeholder::make('is_ahu_registered')
                        ->label('AHU Terdaftar')
                        ->content(fn ($record) => $record?->identity?->getIsAhuRegisteredLabelAttribute())
                        ->columnSpan(1),
                    
                    Placeholder::make('office_coordinate')
                        ->label('Koordinat Kantor')
                        ->content(fn ($record) => $record?->identity?->office_coordinate)
                        ->columnSpan(1),
                    
                    Placeholder::make('nib')
                        ->label('NIB')
                        ->content(fn ($record) => $record?->identity?->nib)
                        ->columnSpan(1),
                    
                    Placeholder::make('npwp')
                        ->label('NPWP')
                        ->content(fn ($record) => $record?->identity?->npwp)
                        ->columnSpan(1),
                    
                    Placeholder::make('bussiness_field')
                        ->label('Bidang Usaha')
                        ->content(fn ($record) => $record?->identity?->bussiness_field ?? '-')
                        ->columnSpan(1),
                ])->columns(3),

            Forms\Components\Section::make('B. Narahubung')
                ->schema([
                    Placeholder::make('name')
                        ->label('Nama')
                        ->content(fn ($record) => $record?->contacts?->name ?? '-')
                        ->columnSpan(1),
                    
                    Placeholder::make('identity_number')
                        ->label('Nomor Identitas')
                        ->content(fn ($record) => $record?->contacts?->identity_number ?? '-')
                        ->columnSpan(1),
                    
                    Placeholder::make('address')
                        ->label('Alamat')
                        ->content(fn ($record) => $record?->contacts?->address ?? '-')
                        ->columnSpan(1),
                    
                    Placeholder::make('phone_number')
                        ->label('Nomor Telepon')
                        ->content(fn ($record) => $record?->contacts?->phone_number ?? '-')
                        ->columnSpan(1),
                    
                    Placeholder::make('email')
                        ->label('Email')
                        ->content(fn ($record) => $record?->contacts?->email ?? '-')
                        ->columnSpan(1),
                    
                    Placeholder::make('position')
                        ->label('Jabatan')
                        ->content(fn ($record) => $record?->contacts?->position ?? '-')
                        ->columnSpan(1),
                ])->columns(3),

                Forms\Components\Section::make('C. Detail Badan Usaha')
                ->schema([
                    Placeholder::make('name')
                        ->label('Nama Perusahaan')
                        ->content(fn ($record) => $record?->companyDetail?->name ?? '-')
                        ->columnSpan(1),

                    Placeholder::make('province')
                        ->label('Provinsi')
                        ->content(fn ($record) => $record?->companyDetail?->province ?? '-')
                        ->columnSpan(1),
                    
                    Placeholder::make('city')
                        ->label('Kota')
                        ->content(fn ($record) => $record?->companyDetail?->city ?? '-')
                        ->columnSpan(1),
                    
                    Placeholder::make('street')
                        ->label('Jalan')
                        ->content(fn ($record) => $record?->companyDetail?->street ?? '-')
                        ->columnSpan(1),

                    Placeholder::make('postal_code')
                        ->label('Kode Pos')
                        ->content(fn ($record) => $record?->companyDetail?->postal_code ?? '-')
                        ->columnSpan(1),
                    
                    Placeholder::make('po_box')
                        ->label('PO Box')
                        ->content(fn ($record) => $record?->companyDetail?->po_box ?? '-')
                        ->columnSpan(1),
                    
                    Placeholder::make('phone')
                        ->label('Nomor Telepon')
                        ->content(fn ($record) => $record?->companyDetail?->phone ?? '-')
                        ->columnSpan(1),
                    
                    Placeholder::make('fax')
                        ->label('Fax')
                        ->content(fn ($record) => $record?->companyDetail?->fax ?? '-')
                        ->columnSpan(1),
                    
                    Placeholder::make('email')
                        ->label('Email')
                        ->content(fn ($record) => $record?->companyDetail?->email ?? '-')
                        ->columnSpan(1),
                    
                    Placeholder::make('website')
                        ->label('Website')
                        ->content(fn ($record) => $record?->companyDetail?->website ?? '-')
                        ->columnSpan(1),
                    
                    Placeholder::make('affiliate')
                        ->label('Perusahaan Affiliasi')
                        ->content(fn ($record) => $record?->companyDetail?->affiliate ?? '-')
                        ->columnSpan(1),
                ])->columns(3),

                Forms\Components\Section::make('D. Informasi Lainnya')
                ->schema([
                    Placeholder::make('products')
                        ->label('Produk')
                        ->content(fn ($record) => $record?->otherInformation?->products ?? '-')
                        ->columnSpan(1),
                    
                    Placeholder::make('production_capacity')
                        ->label('Kapasitas Produksi')
                        ->content(fn ($record) => $record?->otherInformation?->production_capacity ?? '-')
                        ->columnSpan(1),
                    
                    Placeholder::make('number_of_employees')
                        ->label('Jumlah Karyawan')
                        ->content(fn ($record) => $record?->otherInformation?->number_of_employees ?? '-')
                        ->columnSpan(1),

                    Placeholder::make('branch_office_name')
                        ->label('Nama Cabang')
                        ->content(fn ($record) => $record?->otherInformation?->branch_office_name ?? '-')
                        ->columnSpan(1),

                    Placeholder::make('branch_office_street')
                        ->label('Jalan Cabang')
                        ->content(fn ($record) => $record?->otherInformation?->branch_office_street ?? '-')
                        ->columnSpan(1),

                    Placeholder::make('branch_office_city')
                        ->label('Kota Cabang')
                        ->content(fn ($record) => $record?->otherInformation?->branch_office_city ?? '-')
                        ->columnSpan(1),

                    Placeholder::make('branch_office_postal_code')
                        ->label('Kode Pos Cabang')
                        ->content(fn ($record) => $record?->otherInformation?->branch_office_postal_code ?? '-')
                        ->columnSpan(1),

                    Placeholder::make('affiliate_company')
                        ->label('Perusahaan Affiliasi')
                        ->content(fn ($record) => $record?->otherInformation?->affiliate_company ?? '-')
                        ->columnSpan(1),

                    Placeholder::make('affiliate_company_street')
                        ->label('Jalan Perusahaan Affiliasi')
                        ->content(fn ($record) => $record?->otherInformation?->affiliate_company_street ?? '-')
                        ->columnSpan(1),

                    Placeholder::make('affiliate_company_city')
                        ->label('Kota Perusahaan Affiliasi')
                        ->content(fn ($record) => $record?->otherInformation?->affiliate_company_city ?? '-')
                        ->columnSpan(1),

                    Placeholder::make('affiliate_company_postal_code')
                        ->label('Kode Pos Perusahaan Affiliasi')
                        ->content(fn ($record) => $record?->otherInformation?->affiliate_company_postal_code ?? '-')
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