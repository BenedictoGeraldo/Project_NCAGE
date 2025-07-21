<?php

namespace App\Filament\Resources\NcageApplicationResource\Pages;

use App\Filament\Resources\NcageApplicationResource;
use App\Models\NcageApplication;
use App\Models\NcageRecord;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class ValidateRequest extends Page
{
    protected static string $resource = NcageApplicationResource::class;
    protected static string $view = 'filament.resources.ncage-application-resource.pages.validate-request';
    protected static ?string $title = 'Validasi';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public ?int $recordId = null; // simpan ID saja
    public ?array $data = [];

    public function mount(int $record): void
    {
        $this->recordId = $record;

        $application = NcageApplication::findOrFail($this->recordId);

        if ($application->status_id !== 4) {
            Notification::make()
                ->title('Permohonan tidak dalam status input sertifikat.')
                ->danger()
                ->send();

            redirect()->route('filament.resources.ncage-application-resource.pages.index');
        }

        $this->form->fill();
    }

    protected function getRecord(): NcageApplication
    {
        return NcageApplication::with(['identity', 'contacts', 'companyDetail', 'otherInformation'])->findOrFail($this->recordId);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('certificate')
                    ->label('')
                    ->disk('public')
                    ->directory(fn () => "uploads/{$this->getRecord()->user_id}")
                    ->required()
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                    ->maxSize(10240),
            ])
            ->statePath('data')
            ->model($this->getRecord());
    }

    protected function getActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Sertifikat')
                ->action(function () {
                    $record = $this->getRecord();

                    // Ambil objek TemporaryUploadedFile
                    $tempFile = collect($this->data['certificate'])->first();
                    // dd($tempFile);

                    if (!$tempFile) {
                        Notification::make()
                            ->title('File sertifikat tidak ditemukan.')
                            ->danger()
                            ->send();
                        return;
                    }

                    // Tentukan direktori tujuan
                    $targetDirectory = 'uploads/' . Str::slug($record->companyDetail->name, '_') . '/sertifikat';
                    // dd($targetDirectory);

                    // Simpan file ke disk 'public' dan ambil path-nya
                    $storedPath = $tempFile->storeAs($targetDirectory, $tempFile->getClientOriginalName(), 'public');

                    // Update data JSON
                    $documents = $record->documents ? json_decode($record->documents, true) : [];
                    $documents['sertifikat_nspa'] = $storedPath;

                    $record->update([
                        'documents' => json_encode($documents),
                        'status_id' => 5,
                    ]);

                    // Hapus file sementara
                    $tempFile->delete();

                    // Ambil record terakhir dan kode terakhir
                    $lastRecord = NcageRecord::orderBy('id', 'desc')->first();

                    if ($lastRecord && preg_match('/^(\d+)([A-Z])$/', $lastRecord->ncage_code, $matches)) {
                        // $matches[1] = angka (string), $matches[2] = huruf
                        $number = intval($matches[1]) + 1; // tambah 1
                        $letter = $matches[2];

                        // Format ulang angka dengan padding 4 digit (sesuai contoh)
                        $newCode = str_pad($number, strlen($matches[1]), '0', STR_PAD_LEFT) . $letter;
                    } else {
                        // Kalau tidak ada record atau format beda, mulai dari default
                        $newCode = '0001Z';
                    }

                    NcageRecord::updateOrCreate(
                    ['ncage_application_id' => $record->id],
                    [
                        'ncage_code' => $newCode,
                        'ncagesd' => 'A',
                        'toec' => $record->identity->entity_type,
                        'entity_name' => $record->companyDetail->name,
                        'street' => $record->companyDetail->street,
                        'city' => $record->companyDetail->city,
                        'psc' => $record->companyDetail->postal_code,
                        'country' => 'INDONESIA',
                        'ctr' => 'IDN',
                        'stt' => $record->companyDetail->province,
                        'is_sam_requested' => 0,
                        'tel' => $record->companyDetail->phone . ' ' . $record->contacts->phone,
                        'fax' => $record->companyDetail->fax,
                        'ema' => $record->companyDetail->email,
                        'www' => $record->companyDetail->website,
                        'pob' => $record->companyDetail->po_box,
                    ]);

                    Notification::make()
                        ->title('Sertifikat berhasil disimpan.')
                        ->success()
                        ->send();

                    redirect()->route('filament.admin.resources.ncage-applications.index');
                })
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Penyimpanan')
                ->modalSubheading('Apakah Anda yakin ingin menyimpan sertifikat ini?')
                ->color('success'),
        ];
    }

    public function getViewData(): array
    {
        return [
            'title' => $this->getTitle(), // Ambil judul dari properti statis
        ];
    }
}
