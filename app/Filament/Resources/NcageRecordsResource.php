<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NcageRecordsResource\Pages;
use App\Models\NcageRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Grouping\Group;
use Konnco\FilamentImport\Actions\ImportAction;

class NcageRecordsResource extends Resource
{
    protected static ?string $model = NcageRecord::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Mendefinisikan form untuk membuat dan mengedit data
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Utama')
                    ->schema([
                        Forms\Components\TextInput::make('ncage_code')
                            ->required()
                            ->maxLength(10)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('entity_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('status_code')
                            ->label('Status (NCAGESD)')
                            ->maxLength(5),
                        Forms\Components\TextInput::make('entity_type_code')
                            ->label('Tipe Entitas (TOEC)')
                            ->maxLength(5),
                    ])->columns(2),

                Forms\Components\Section::make('Alamat & Kontak')
                    ->schema([
                        Forms\Components\Textarea::make('street')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('city'),
                        Forms\Components\TextInput::make('state_province')
                            ->label('Provinsi'),
                        Forms\Components\TextInput::make('postal_code')
                            ->label('Kode Pos'),
                        Forms\Components\TextInput::make('country'),
                        Forms\Components\TextInput::make('phone_number')
                            ->tel(),
                        Forms\Components\TextInput::make('fax_number')
                            ->tel(),
                        Forms\Components\TextInput::make('email')
                            ->email(),
                        Forms\Components\TextInput::make('website')
                            ->url(),
                    ])->columns(2),
            ]);
    }

    // Mendefinisikan tabel untuk menampilkan data
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ncage_code')
                    ->label('Kode NCAGE')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('entity_name')
                    ->label('Nama Entitas')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('toec') // Diubah dari status_code untuk grouping
                    ->label('Tipe Entitas')
                    ->sortable(),
                Tables\Columns\TextColumn::make('city')
                    ->label('Kota')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stt') // Diubah dari state_province untuk grouping
                    ->label('Provinsi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('creation_date')
                    ->label('Tgl. Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                // Logika ImportAction yang sudah kita perbaiki
                ImportAction::make()
                    ->handleRecordCreation(function(array $data): NcageRecord {
                        return NcageRecord::updateOrCreate(
                            ['ncage_code' => $data['ncage_code'] ?? null],
                            [
                                // Data untuk di-update atau dibuat baru
                                'ncagesd'           => $data['ncagesd'],
                                'toec'              => $data['toec'],
                                'entity_name'       => $data['entity_name'],
                                'street'            => $data['street_st12'],
                                'city'              => $data['city_cit'],
                                'psc'               => $data['post_code_physical_address_psc'],
                                'country'           => $data['country'],
                                'ctr'               => $data['iso_ctr'],
                                'stt'               => $data['stateprovince_stt'],
                                'ste'               => $data['fips_state_ste'],
                                'is_sam_requested'  => filter_var($data['cage_code_requested_for_sam'], FILTER_VALIDATE_BOOLEAN),
                                'remarks'           => $data['remarks'],
                                'last_change_date_international' => $data['date_last_change_international'],
                                'change_date'       => $data['change_date'],
                                'creation_date'     => $data['creation_date'],
                                'load_date'         => $data['load_date'],
                                'national'          => $data['national'],
                                'nac'               => $data['nac'],
                                'idn'               => $data['idn'],
                                'bar'               => $data['bar'],
                                'nai'               => $data['nai'],
                                'cpv'               => $data['cpv'],
                                'uns'               => $data['uns'],
                                'sic'               => $data['sic'],
                                'tel'               => $data['voice_telephone_number_tel'],
                                'fax'               => $data['telefax_number_fax'],
                                'ema'               => $data['email_ema'],
                                'www'               => $data['www_www'],
                                'pob'               => $data['post_office_box_number_pob'],
                                'pcc'               => $data['city_postal_address_pcc'],
                                'pcs'               => $data['post_code_postal_address_pcs'],
                                'rp1_5'             => $data['replaced_by_rp15'],
                                'nmcrl_ref_count'   => $data['nmcrl_reference_count'] ? (int)$data['nmcrl_reference_count'] : null,
                            ]
                        );
                    })
            ])
            ->groups([
                Group::make('toec')
                    ->label('Tipe Entitas'),
                Group::make('stt')
                    ->label('Provinsi'),
            ])
            ->defaultGroup('toec')
            ->paginated();
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNcageRecords::route('/'),
            'create' => Pages\CreateNcageRecords::route('/create'),
            'edit' => Pages\EditNcageRecords::route('/{record}/edit'),
        ];
    }
}
