@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('level/create') }}">Tambah</a>
            <button class="btn btn-sm btn-success mt-1 btn-modal" data-url="{{ url('level/create_ajax') }}">Tambah Ajax</button>
            <button class="btn btn-sm btn-info mt-1 btn-modal" data-url="{{ url('level/import') }}">Import</button>
        </div>
    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered table-striped table-hover table-sm" id="table_level" style="width: 100%;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Level</th>
                    <th>Nama Level</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
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
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>

<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    var tableLevel;
    $(document).ready(function() {
        tableLevel = $('#table_level').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ url('level/list') }}",
                type: "POST",
                dataType: "json",
                data: function(d) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
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
                    data: "level_kode",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "level_nama",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "aksi",
                    orderable: false,
                    searchable: false
                }
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

        // AJAX import submit handler
        $(document).on('submit', '#form-import', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            $.ajax({
                url: '{{ url("/level/import_ajax") }}',
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
                        tableLevel.ajax.reload();
                    } else {
                        $('.error-text').text('');
                        $.each(response.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    console.log('Error Details:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan: ' + (xhr.status === 404 ? 'URL tidak ditemukan' : xhr.statusText)
                    });
                }
            });
        });
    });
</script>
@endpush
