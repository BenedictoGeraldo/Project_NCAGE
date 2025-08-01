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
use App\Notifications\CertificateIssued;

class ValidateRequest extends Page
{
    protected static string $resource = NcageApplicationResource::class;
    protected static string $view = 'filament.resources.ncage-application-resource.pages.validate-request';
    protected static ?string $title = 'Validasi';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public ?int $recordId = null; // simpan ID saja
    public ?array $data = [];
    public static function canAccess(array $parameters = []): bool
    {
        return auth()->check() && auth()->user()->can('validate_ncage::application');
    }

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
                ->label('Upload Sertifikat')
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

                    $record->update([
                        'status_id' => 5,
                        'international_certificate_path' => $storedPath,
                        'validated_by' => auth('admin')->user()->id
                    ]);

                    $ncage = NcageRecord::where('ncage_code', $record->ncage_code)->first();

                    $ncage->change_date = now();

                    if (empty($ncage->creation_date)) {
                        $ncage->creation_date = now();
                    }

                    $ncage->save();

                    // Hapus file sementara
                    $tempFile->delete();


                    //trigger notifikasi sertifikat telah muncul
                    if ($user = $record->user) {
                        $user->notify(new CertificateIssued());
                    }

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
