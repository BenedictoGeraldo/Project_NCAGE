@extends('layouts.filament-standalone')


@section('content')
    <a href="{{ route('filament.admin.resources.ncage-applications.index') }}" class="mb-4 d-inline-block">← Kembali ke Daftar Permohonan</a>

    <div class="row">
        <!-- Bagian Kiri: Data Permohonan -->
        <div class="col-md-6">
            <h1>Data Permohonan</h1>
            <p><strong>Jenis Permohonan:</strong> {{ $applicationIdentity->application_type ?? '-' }}</p>
            <p><strong>Tanggal Pengajuan:</strong> {{ $applicationIdentity->submission_date ?? '-' }}</p>
            <p><strong>Tipe Entitas:</strong> {{ $applicationIdentity->entity_type ?? '-' }}</p>
        </div>

        <!-- Bagian Kanan: Preview Dokumen -->
        <div class="col-md-6">
            <h2>Preview Dokumen</h2>
            @foreach ($documents as $name => $path)
                <h3>{{ ucfirst(str_replace('_', ' ', $name)) }}</h3>
                <iframe src="{{ asset($path) }}" width="100%" height="400px" class="mb-3"></iframe>
            @endforeach
        </div>
    </div>
@endsection