@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('barang/create') }}">Tambah</a>
            <button class="btn btn-sm btn-success mt-1 btn-modal" data-url="{{ url('barang/create_ajax') }}">Tambah Ajax</button>
            <button class="btn btn-sm btn-info mt-1 btn-modal" data-url="{{ url('barang/import') }}">Import</button>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <div class="mb-3">
            <select class="form-control filter_kategori" style="width: 200px; display: inline-block;">
                <option value="">Semua Kategori</option>
                @foreach($kategori as $kategori)
                    <option value="{{ $kategori->kategori_id }}">{{ $kategori->kategori_nama }}</option>
                @endforeach
            </select>
        </div>
        <table class="table table-bordered table-striped table-hover table-sm" id="table_barang" style="width: 100%;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
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
<script>
    function modalAction(url = ''){
        $('#myModal').load(url, function(){
            $('#myModal').modal('show');
        });
    }

    var dataBarang;
    $(document).ready(function(){
        dataBarang = $('#table_barang').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                "url": "{{ url('barang/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(d) {
                    d.filter_kategori = $('.filter_kategori').val();
                }
            },
            columns: [
                { data: "DT_RowIndex", className: "text-center", width: "5%", orderable: false, searchable: false },
                { data: "barang_kode", width: "10%", orderable: true, searchable: true },
                { data: "barang_nama", width: "25%", orderable: true, searchable: true },
                { data: "harga_beli", width: "13%", orderable: true, searchable: false, render: data => new Intl.NumberFormat('id-ID').format(data) },
                { data: "harga_jual", width: "13%", orderable: true, searchable: false, render: data => new Intl.NumberFormat('id-ID').format(data) },
                { data: "kategori.kategori_nama", width: "17%", orderable: true, searchable: false },
                { data: "aksi", className: "text-center", width: "27%", orderable: false, searchable: false }
            ]
        });

        $(document).on('click', '.btn-modal', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            if (url) {
                modalAction(url);
            } else {
                console.warn('URL untuk modal tidak ditemukan!');
            }
        });

        $('.filter_kategori').change(function() {
            dataBarang.draw();
        });
    });
</script>
@endpush