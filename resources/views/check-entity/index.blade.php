@extends('layouts.main') {{-- Sesuaikan dengan layout utama Anda --}}

@section('title', 'Cek Entitas') {{-- Judul halaman --}}

@section('content')
<div class="container py-4">
    <div class="card shadow-sm p-4 rounded-4 border-0">
        <div class="card-body">
            <h4 class="fw-bold mb-3 text-center">Data Entitas Terdaftar</h4>
            <hr class="border-2 border-dark-red opacity-100 mb-4" />

            @if($ncageRecords->isEmpty())
                <p class="text-center text-muted">Belum ada entitas terdaftar.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode NCAGE</th>
                                <th>Nama Perusahaan</th>
                                <th>Status</th>
                                <th>Provinsi</th>
                                <th>Kota</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ncageRecords as $index => $record)
                            <tr>
                                <td>{{ $ncageRecords->firstItem() + $index }}</td>
                                <td>{{ $record->ncage_code }}</td>
                                <td>{{ $record->entity_name }}</td>
                                <td>{{ $record->ncagesd }}</td> {{-- Tampilkan status --}}
                                <td>{{ $record->stt }}</td>
                                <td>{{ $record->city }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- Pagination Links --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $ncageRecords->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection