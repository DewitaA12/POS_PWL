@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <!-- <a class="btn btn-sm btn-primary mt-1" href="{{ url('user/create') }}">Tambah</a> -->
            <a href="{{ url('/user/export_excel') }}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file-excel"></i> Export User Excel</a>
            <a href="{{ url('/user/export_pdf') }}" class="btn btn-sm btn-danger mt-1"><i class="fa fa-file-pdf"></i> Export User PDF</a>
            <button class="btn btn-sm btn-success mt-1 btn-modal" data-url="{{ url('user/create_ajax') }}">Tambah Ajax</button>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Filter:</label>
                    <div class="col-3">
                        <select class="form-control" id="level_id" name="level_id" required>
                            <option value="">- Semua -</option>
                            @foreach($level as $item)
                                <option value="{{ $item->level_id }}">{{ $item->level_nama }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Level Pengguna</small>
                    </div>
                </div>
            </div>
        </div>

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
            <tbody></tbody>
        </table>
    </div>
</div>

{{-- Modal container untuk AJAX --}}
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

    .btn-group-aksi a,
    .btn-group-aksi form {
        display: inline-block;
        margin-right: 5px;
    }
</style>
@endpush

@push('js')
<script> 
    function modalAction(url = ''){
            $('#myModal').load(url,function(){
            $('#myModal').modal('show');
            });
        }
        var dataUser;
        $(document).ready(function(){
            dataUser = $('#table_user').DataTable({
                // serverSide: true, jika ingin menggunakan server side processing
                serverSide: true,
                ajax: {
                    "url": "{{ url('user/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        d._level_id = $('#level_id').val();
                    }
                },
            columns: [
                {
                    data: "DT_RowIndex", 
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "username",
                    className: "", 
                    orderable: true,
                    searchable: true
                },
                {
                    data: "nama",
                    className: "", 
                    orderable: true,
                    searchable: true
                },
                {
                    data: "level.level_nama", 
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

        // Event saat filter level diubah
        $('#level_id').on('change', function() {
            dataUser.ajax.reload();
        });

        // Event handler tombol "Tambah Ajax"
        $(document).on('click', '.btn-modal', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            if (url) {
                modalAction(url);
            } else {
                console.warn('URL untuk modal tidak ditemukan!');
            }
        });
    });
</script>
@endpush
