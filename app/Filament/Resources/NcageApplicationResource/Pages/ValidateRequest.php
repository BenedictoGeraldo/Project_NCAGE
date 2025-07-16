<?php

namespace App\Filament\Resources\NcageApplicationResource\Pages;

use App\Filament\Resources\NcageApplicationResource;
use App\Models\NcageApplication;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ValidateRequest extends Page
{
    protected static string $resource = NcageApplicationResource::class;

    protected static string $view = 'filament.resources.ncage-application-resource.pages.validate-request';

    protected static ?string $title = 'Validasi';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected ?NcageApplication $record = null;
    public ?array $data = [];

    public function mount(int $record): void
    {
        $this->record = NcageApplication::findOrFail($record);

        if ($this->record->status_id !== 4) { // Hanya untuk status "Proses Validasi/Input Sertifikat"
            Notification::make()
                ->title('Permohonan tidak dalam status input sertifikat.')
                ->danger()
                ->send();
            redirect()->route('filament.resources.ncage-application-resource.pages.index');
        }

        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('certificate')
                    ->label('')
                    ->disk('public')
                    ->directory(fn () => "uploads/{$this->record->user_id}")
                    ->required()
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                    ->maxSize(10240), // 10MB maksimum
            ])
            ->statePath('data')
            ->model($this->record);
    }

    protected function getActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Sertifikat')
                ->action(function () {
                    // $this->record->update([
                    //     'certificate_path' => $this->data['certificate'],
                    //     'status_id' => 5, // Ubah status ke "Selesai"
                    // ]);
                    Notification::make()
                        ->title('Sertifikat berhasil disimpan.')
                        ->success()
                        ->send();
                    redirect()->route('filament.resources.ncage-applications.index');
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
