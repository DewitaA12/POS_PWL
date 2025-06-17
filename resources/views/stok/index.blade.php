@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <!-- <a class="btn btn-sm btn-primary mt-1" href="{{ url('stok/create') }}">Tambah</a> -->
             <a href="{{ url('/stok/export_excel') }}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file-excel"></i> Export Stok Excel</a>
             <a href="{{ url('/stok/export_pdf') }}" class="btn btn-sm btn-danger mt-1"><i class="fa fa-file-pdf"></i> Export Stok PDF</a>
            <button class="btn btn-sm btn-success mt-1 btn-modal" data-url="{{ url('stok/create_ajax') }}">Tambah Ajax</button>
            <button class="btn btn-sm btn-info mt-1 btn-modal" data-url="{{ url('stok/import') }}">Import</button>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered table-striped table-hover table-sm" id="table_stok" style="width: 100%;">
            <thead>
            <tr>
            <th>#</th>
            <th>Tanggal</th>
            <th>Nama Barang</th>
            <th>Nama Supplier</th>
            <th>Jumlah</th>
            <th>User Input</th>
            <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <meta name="csrf-token" content="{{ csrf_token() }}">
    </div>
</div>

<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" 
    data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
<style>
    table.dataTable th {
        position: relative;
        padding-right: 25px !important;
        white-space: nowrap;
    }

    table.dataTable th.sorting::after,
    table.dataTable th.sorting_asc::after,
    table.dataTable th.sorting_desc::after {
        right: 5px;
    }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    var tablestok;
    $(document).ready(function() {
        tablestok = $('#table_stok').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ url('stok/list') }}",
                type: "POST",
                data: function(d) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            columns: [
                { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                { data: "stok_tanggal", className: "", orderable: true, searchable: true },
                { data: "barang_nama", className: "", orderable: true, searchable: true },
                { data: "supplier_nama", className: "", orderable: true, searchable: true },
                { data: "stok_jumlah", className: "text-center", orderable: true, searchable: false },
                { data: "user_nama", className: "", orderable: false, searchable: true },
                { data: "aksi", className: "", orderable: false, searchable: false }
            ]
        });

        $(document).on('click', '.btn-modal', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            if (url) {
                modalAction(url);
            }
        });

        $('#form-import').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            $.ajax({
                url: '{{ url("/stok/import_ajax") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        tablestok.ajax.reload();
                    } else {
                        $('.error-text').text('');
                        $.each(response.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: xhr.responseJSON?.message || xhr.statusText
                    });
                }
            });
        });
    });
</script>
@endpush
