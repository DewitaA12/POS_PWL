@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('supplier/create') }}">Tambah</a>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered table-striped table-hover table-sm" id="table_supplier" style="width: 100%;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Supplier</th>
                    <th>Nama Supplier</th>
                    <th>Alamat</th>
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
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#table_supplier').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ url('supplier/list') }}",
                type: "POST",
                dataType: "json",
            },
            autoWidth: true,
            columns: [
                { data: 'DT_RowIndex', className: "text-center", orderable: false, searchable: false },
                { data: 'supplier_kode', orderable: true, searchable: true },
                { data: 'supplier_nama', orderable: true, searchable: true },
                { data: 'alamat', orderable: true, searchable: true },
                { data: 'aksi', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endpush
