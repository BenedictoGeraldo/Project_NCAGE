@extends('layouts.main') {{-- Sesuaikan dengan layout utama Anda --}}

@section('title', 'Cek Entitas') {{-- Judul halaman --}}

@section('styles')
<style>
    body{
        font-family: 'poppins' !important;
    }
    /* Mengubah warna font untuk angka halaman yang tidak aktif */
    .dataTables_wrapper .pagination .page-item .page-link {
        color: #000000; /* Warna hitam murni sesuai permintaan */
    }

    /* Mengubah warna font untuk angka halaman yang aktif */
    .dataTables_wrapper .pagination .page-item.active .page-link {
        color: #ffffff; /* Warna putih */
        background-color: #6A040F;
        border: none; 
    }

    /* Mengubah warna font untuk tombol Previous/Next saat hover */
    .dataTables_wrapper .pagination .page-item .page-link:hover {
        color: #364350; /* Warna biru gelap saat hover */
    }

    /* Mengubah warna font untuk tombol Previous/Next saat disabled */
    .dataTables_wrapper .pagination .page-item.disabled .page-link {
        color: #adb5bd; /* Warna abu-abu lebih terang */
    }

    /* Mengubah warna font untuk teks "Showing X to Y of Z entries" */
    .dataTables_info {
        color: #343a40; /* Warna hitam gelap */
    }

    .line{
        color: #010000;
        height: 5px !important;
        border-radius: 10px;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="card shadow-sm p-4 rounded-4 border-0">
        <div class="card-body">
            <h4 class="fw-bolder fs-1 text-center">Cek Entitas</h4>
            <hr class="border-5 opacity-100 mb-4 line" />

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
