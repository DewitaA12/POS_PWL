@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('user/create') }}">Tambah</a>
        </div>
    </div>
    <div class="card-body">
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
        <table class="table table-bordered table-striped table-hover table-sm" id="table_user" style="width: 100%;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Level</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                </tbody>
        </table>
    </div>
</div>
@endsection

@push('css')
<style>
    table.dataTable th {
        position: relative;
        padding-right: 25px !important; /* ruang buat icon sort */
        white-space: nowrap; /* biar gak turun baris */
    }

    /* Optional: center icon panah */
    table.dataTable th.sorting::after,
    table.dataTable th.sorting_asc::after,
    table.dataTable th.sorting_desc::after {
        right: 5px; /* atur posisi icon panah */
    }
</style>
@endpush

@push('js')
<script>
    $(document).ready(function() {
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });

        var datatable = $('#table_user').DataTable({
            // serverSide: true, jika ingin menggunakan server side processing
            serverSide: true,
            ajax: {
                "url": "{{ url('user/list') }}",
                "dataType": "json",
                "type": "POST"
            },
            autoWidth: true,
            columns: [
                // nomor urut dari laravel datatable addIndexColumn()
                {
                    data: 'DT_RowIndex',
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "username",
                    className: "",
                    // orderable: true, jika ingin kolom ini bisa diurutkan
                    orderable: true,
                    // searchable: true, jika ingin kolom ini bisa dicari
                    searchable: true
                },
                {
                    data: "nama",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    // mengambil data level hasil dari ORM berelasi
                    data: "level",
                    className: "",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "aksi",
                    className: "",
                    orderable: false,
                    searchable: false
                }
            ]
        });
    });
</script>
@endpush