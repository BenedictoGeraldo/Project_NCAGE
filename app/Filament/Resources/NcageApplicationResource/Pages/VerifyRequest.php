<?php

namespace App\Filament\Resources\NcageApplicationResource\Pages;

use App\Filament\Resources\NcageApplicationResource;
use App\Models\NcageApplication;
use App\Models\ApplicationIdentity;
use Filament\Resources\Pages\Page;

class VerifyRequest extends Page
{
    protected static string $resource = NcageApplicationResource::class;

    protected static string $view = 'filament.resources.ncage-application-resource.pages.verify-request';

    public $recordId;

    public $applicationIdentity;

    public $applicationContacts;
    public $applicationCompany;
    public $applicationOtherInformation;
    public $documents;

    public function mount($record): void
    {
        $this->recordId = $record;

        $application = NcageApplication::with(['identity', 'contacts', 'companyDetail', 'otherInformation'])->findOrFail($record);
        $this->applicationIdentity = $application->identity; // pastikan relasi `identity()` dibuat di model
        $this->applicationContacts = $application->contacts;
        $this->applicationCompany = $application->companyDetail;
        $this->applicationOtherInformation = $application->otherInformation;

        $this->documents = json_decode($application->documents, true);
    }

    protected function getViewData(): array
    {
        return [
            'applicationIdentity' => $this->applicationIdentity,
            'applicationContacts' => $this->applicationContacts,
            'applicationCompany' => $this->applicationCompany,
            'applicationOtherInformation' => $this->applicationOtherInformation,
            'documents' => $this->documents,
        ];
    }

    public function getLayout(): string
    {
        return 'layouts.filament-standalone';
    }
}
