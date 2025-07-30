@extends('layouts.main') {{-- Sesuaikan dengan layout utama Anda --}}

@section('title', 'Cek Entitas') {{-- Judul halaman --}}

@section('styles')
<style>
    font-family: 'poppins' !important;
</style>

@section('content')
<div class="container py-4">
    <div class="card shadow-sm p-4 rounded-4 border-0">
        <div class="card-body">
            <h4 class="fw-bold mb-3 text-center">Cek Entitas</h4>
            <hr class="border-2 border-dark-red opacity-100 mb-4" />

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="ncagerecords-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode NCAGE</th>
                            <th>Nama Perusahaan</th>
                            <th>Status</th>
                            <th>Provinsi</th>
                            <th>Kota</th>
                            {{-- Tambahkan <th> lain jika ada kolom tambahan yang ingin Anda tampilkan --}}
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Data akan diisi secara otomatis oleh Yajra Datatables via AJAX --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- --- Bagian CSS dan JavaScript untuk Datatables --- --}}

{{-- CSS Datatables untuk styling Bootstrap 5 --}}
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

{{-- CATATAN PENTING: jQuery HARUS dimuat di layouts.main.blade.php Anda, SEBELUM @stack('scripts'). --}}
{{-- Baris di bawah ini dikomentari karena seharusnya sudah di-load di layout utama. --}}
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}

{{-- Script utama Datatables --}}
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
{{-- Script integrasi Datatables dengan Bootstrap 5 --}}
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script type="text/javascript">
    $(function () {
        // Inisialisasi Datatables pada tabel dengan ID 'ncagerecords-table'
        var table = $('#ncagerecords-table').DataTable({
            processing: true, // Menampilkan indikator loading saat data diambil
            serverSide: true, // Sangat penting: Mengaktifkan pemrosesan di sisi server untuk performa
            ajax: {
                url: "{{ route('entity-check.get-data') }}", // URL ke route AJAX yang kita buat
                type: "GET"
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false}, // Kolom 'No.'
                {data: 'ncage_code', name: 'ncage_code'},
                {data: 'entity_name', name: 'entity_name'},
                {data: 'ncagesd', name: 'ncagesd'},
                {data: 'stt', name: 'stt'},
                {data: 'city', name: 'city'},
                // Tambahkan baris ini untuk setiap kolom lain yang Anda miliki
            ],
        });
    });
</script>
@endpush
