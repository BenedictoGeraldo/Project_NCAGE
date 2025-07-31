<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyResource\Pages;
use App\Filament\Resources\SurveyResource\RelationManagers;
use App\Models\Survey;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ncage_application_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('q1_kesesuaian_persyaratan')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('q2_kemudahan_prosedur')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('q3_kecepatan_pelayanan')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('q4_kewajaran_biaya')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('q5_kesesuaian_produk')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('q6_kompetensi_petugas')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('q7_perilaku_petugas')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('q8_kualitas_sarana')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('q9_penanganan_pengaduan')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        // Hitung rata-rata untuk setiap pertanyaan
        $avg_q1 = Survey::query()->avg('q1_kesesuaian_persyaratan');
        $avg_q2 = Survey::query()->avg('q2_kemudahan_prosedur');
        $avg_q3 = Survey::query()->avg('q3_kecepatan_pelayanan');
        $avg_q4 = Survey::query()->avg('q4_kewajaran_biaya');
        $avg_q5 = Survey::query()->avg('q5_kesesuaian_produk');
        $avg_q6 = Survey::query()->avg('q6_kompetensi_petugas');
        $avg_q7 = Survey::query()->avg('q7_perilaku_petugas');
        $avg_q8 = Survey::query()->avg('q8_kualitas_sarana');
        $avg_q9 = Survey::query()->avg('q9_penanganan_pengaduan');
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ncageApplication.user.company_name')
                    ->numeric()
                    ->label('Entitas')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ncageApplication.user.name')
                    ->numeric()
                    ->label('Diisi Oleh')
                    ->sortable(),
                Tables\Columns\TextColumn::make('q1_kesesuaian_persyaratan')
                    ->numeric()
                    ->label('Q1: Kesesuaian Persyaratan ('. number_format($avg_q1, 2) . ')')
                    ->formatStateUsing(fn (string $state): string => match ((int)$state) {
                        1 => '1. Tidak Sesuai',
                        2 => '2. Kurang Sesuai',
                        3 => '3. Sesuai',
                        4 => '4. Sangat Sesuai',
                        default => 'N/A',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('q2_kemudahan_prosedur')
                    ->numeric()
                    ->label('Q2: Kemudahan Prosedur ('. number_format($avg_q2, 2) . ')')
                    ->formatStateUsing(fn (string $state): string => match ((int)$state) {
                        1 => '1. Tidak Mudah',
                        2 => '2. Kurang Mudah',
                        3 => '3. Mudah',
                        4 => '4. Sangat Mudah',
                        default => 'N/A',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('q3_kecepatan_pelayanan')
                    ->numeric()
                    ->label('Q3: Kecepatan Pelayanan ('. number_format($avg_q3, 2) . ')')
                    ->formatStateUsing(fn (string $state): string => match ((int)$state) {
                        1 => '1. Tidak Cepat',
                        2 => '2. Kurang Cepat',
                        3 => '3. Cepat',
                        4 => '4. Sangat Cepat',
                        default => 'N/A',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('q4_kewajaran_biaya')
                    ->numeric()
                    ->label('Q4: Kewajaran Biaya ('. number_format($avg_q4, 2) . ')')
                    ->formatStateUsing(fn (string $state): string => match ((int)$state) {
                        1 => '1. Sangat Mahal',
                        2 => '2. Mahal',
                        3 => '3. Murah',
                        4 => '4. Gratis',
                        default => 'N/A',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('q5_kesesuaian_produk')
                    ->numeric()
                    ->label('Q5: Kesesuaian Produk ('. number_format($avg_q5, 2) . ')')
                    ->formatStateUsing(fn (string $state): string => match ((int)$state) {
                        1 => '1. Tidak Sesuai',
                        2 => '2. Kurang Sesuai',
                        3 => '3. Sesuai',
                        4 => '4. Sangat Sesuai',
                        default => 'N/A',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('q6_kompetensi_petugas')
                    ->numeric()
                    ->label('Q6: Kompetensi Petugas ('. number_format($avg_q6, 2) . ')')
                    ->formatStateUsing(fn (string $state): string => match ((int)$state) {
                        1 => '1. Tidak Kompeten',
                        2 => '2. Kurang Kompeten',
                        3 => '3. Kompeten',
                        4 => '4. Sangat Kompeten',
                        default => 'N/A',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('q7_perilaku_petugas')
                    ->numeric()
                    ->label('Q7: Perilaku Petugas ('. number_format($avg_q7, 2) . ')')
                    ->formatStateUsing(fn (string $state): string => match ((int)$state) {
                        1 => '1. Tidak Sopan & Ramah',
                        2 => '2. Kurang Sopan & Ramah',
                        3 => '3. Sopan & Ramah',
                        4 => '4. Sangat Sopan & Ramah',
                        default => 'N/A',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('q8_kualitas_sarana')
                    ->numeric()
                    ->label('Q8: Kualitas Sarana ('. number_format($avg_q8, 2) . ')')
                    ->formatStateUsing(fn (string $state): string => match ((int)$state) {
                        1 => '1. Buruk',
                        2 => '2. Cukup',
                        3 => '3. Baik',
                        4 => '4. Sangat Baik',
                        default => 'N/A',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('q9_penanganan_pengaduan')
                    ->numeric()
                    ->label('Q9: Penanganan Pengaduan ('. number_format($avg_q9, 2) . ')')
                    ->formatStateUsing(fn (string $state): string => match ((int)$state) {
                        1 => '1. Tidak Ada',
                        2 => '2. Ada Tetapi Tidak Berfungsi',
                        3 => '3. Berfungsi Kurang Maksimal',
                        4 => '4. Dikelola Dengan Baik',
                        default => 'N/A',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListSurveys::route('/'),
            // 'create' => Pages\CreateSurvey::route('/create'),
            'edit' => Pages\EditSurvey::route('/{record}/edit'),
        ];
    }
}
