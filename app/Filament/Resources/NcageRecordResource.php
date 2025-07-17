<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NcageRecordResource\Pages;
use App\Models\NcageRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class NcageRecordResource extends Resource
{
    protected static ?string $model = NcageRecord::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static ?string $navigationLabel = 'NCAGE Records';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Utama & Status')
                    ->schema([
                        Forms\Components\TextInput::make('ncage_code')
                            ->required()->maxLength(10)->unique(ignoreRecord: true)->columnSpan(1),
                        Forms\Components\TextInput::make('entity_name')
                            ->required()->maxLength(255)->columnSpan(2),
                        Forms\Components\TextInput::make('ncagesd')
                            ->label('Status Kode (NCAGESD)')->maxLength(5),
                        Forms\Components\TextInput::make('toec')
                            ->label('Tipe Entitas (TOEC)')->maxLength(5),
                        Forms\Components\Toggle::make('is_sam_requested')
                            ->label('Diminta untuk SAM'),
                    ])->columns(3),

                Forms\Components\Section::make('Alamat Fisik')
                    ->schema([
                        Forms\Components\Textarea::make('street')
                            ->label('Jalan')->columnSpanFull(),
                        Forms\Components\TextInput::make('city')->label('Kota'),
                        Forms\Components\TextInput::make('stt')->label('Provinsi (STT)'),
                        Forms\Components\TextInput::make('psc')->label('Kode Pos (PSC)'),
                        Forms\Components\TextInput::make('country')->label('Negara'),
                        Forms\Components\TextInput::make('ctr')->label('Kode Negara (CTR)'),
                        Forms\Components\TextInput::make('ste')->label('Kode State (STE)'),
                    ])->columns(2),

                Forms\Components\Section::make('Alamat Surat')
                    ->schema([
                        Forms\Components\TextInput::make('pob')->label('PO BOX'),
                        Forms\Components\TextInput::make('pcc')->label('Kota (Alamat Pos)'),
                        Forms\Components\TextInput::make('pcs')->label('Kode Pos (Alamat Pos)'),
                    ])->columns(3),

                Forms\Components\Section::make('Kontak')
                    ->schema([
                        Forms\Components\TextInput::make('tel')->label('Telepon'),
                        Forms\Components\TextInput::make('fax')->label('Fax'),
                        Forms\Components\TextInput::make('ema')->label('Email'),
                        Forms\Components\TextInput::make('www')->label('Website'),
                    ])->columns(2),

                Forms\Components\Section::make('Klasifikasi & Referensi')
                    ->schema([
                        Forms\Components\TextInput::make('national')->label('National'),
                        Forms\Components\TextInput::make('nac')->label('NAC'),
                        Forms\Components\TextInput::make('idn')->label('IDN'),
                        Forms\Components\TextInput::make('bar')->label('BAR'),
                        Forms\Components\TextInput::make('nai')->label('NAI'),
                        Forms\Components\TextInput::make('cpv')->label('CPV'),
                        Forms\Components\TextInput::make('uns')->label('UNS'),
                        Forms\Components\TextInput::make('sic')->label('SIC'),
                        Forms\Components\TextInput::make('rp1_5')->label('Digantikan oleh (RP1_5)'),
                        Forms\Components\TextInput::make('nmcrl_ref_count')->label('Jml. Ref. NMCRL')->numeric(),
                        Forms\Components\TextInput::make('ncage_application_id')->label('ID Aplikasi Terkait')->numeric()->readOnly(),
                    ])->columns(3),

                Forms\Components\Section::make('Informasi Tambahan & Tanggal')
                    ->schema([
                        Forms\Components\Textarea::make('remarks')->label('Remarks')->columnSpanFull(),
                        Forms\Components\DateTimePicker::make('creation_date')->label('Tanggal Dibuat'),
                        Forms\Components\DateTimePicker::make('change_date')->label('Tanggal Diubah'),
                        Forms\Components\DateTimePicker::make('load_date')->label('Tanggal Dimuat'),
                        Forms\Components\DatePicker::make('last_change_date_international')->label('Tanggal Ubah Internasional'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // == KOLOM YANG TAMPIL SECARA DEFAULT ==
                Tables\Columns\TextColumn::make('ncage_code')
                    ->label('Kode NCAGE')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('entity_name')
                    ->label('Nama Entitas')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ncagesd')
                    ->label('Status Kode')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('toec')
                    ->label('Tipe Entitas')
                    ->searchable()
                    ->sortable(),

                // == KOLOM TAMBAHAN (TERSEMBUNYI BY DEFAULT) ==
                Tables\Columns\TextColumn::make('ncageApplication.id')
                    ->label('ID Aplikasi Terkait')
                    ->numeric()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('street')
                    ->label('Jalan')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('city')
                    ->label('Kota')->searchable()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('psc')
                    ->label('Kode Pos (Fisik)')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('country')
                    ->label('Negara')->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ctr')
                    ->label('ISO (CTR)')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('stt')
                    ->label('Provinsi')->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ste')
                    ->label('FIPS State (STE)')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_sam_requested')
                    ->label('Req. SAM')->boolean()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('remarks')
                    ->label('Remarks')->limit(30)->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('last_change_date_international')
                    ->label('Tgl. Ubah Internasional')->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('change_date')
                    ->label('Tgl. Ubah')->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('creation_date')
                    ->label('Tgl. Dibuat')->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('load_date')
                    ->label('Tgl. Dimuat')->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('national')
                    ->label('National')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nac')
                    ->label('NAC')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('idn')
                    ->label('IDN')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bar')
                    ->label('BAR')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nai')
                    ->label('NAI')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('cpv')
                    ->label('CPV')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('uns')
                    ->label('UNS')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('sic')
                    ->label('SIC')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tel')
                    ->label('Telepon')->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('fax')
                    ->label('Fax')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ema')
                    ->label('Email')->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('www')
                    ->label('Website')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('pob')
                    ->label('PO BOX')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('pcc')
                    ->label('Kota (Alamat Pos)')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('pcs')
                    ->label('Kode Pos (Alamat Pos)')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rp1_5')
                    ->label('Digantikan Oleh')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nmcrl_ref_count')
                    ->label('Jml. Ref. NMCRL')->numeric()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('downloadCertificate')
                    ->label('Unduh Sertifikat Indonesia')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    // Arahkan ke route yang sudah kita buat
                    ->url(fn (NcageRecord $record): string => route('certificate.download.record', $record))
                    // Buka di tab baru
                    ->openUrlInNewTab()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->paginated();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNcageRecords::route('/'),
            'create' => Pages\CreateNcageRecord::route('/create'),
            'edit' => Pages\EditNcageRecord::route('/{record}/edit'),
        ];
    }
}
