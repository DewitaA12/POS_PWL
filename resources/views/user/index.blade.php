@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('user/create') }}">Tambah</a>
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

        <div class="row mb-3">
            <div class="col-md-4">
                <select class="form-control" id="level_id" name="level_id" required>
                    <option value="">- Semua -</option>
                    @foreach($level as $item)
                        <option value="{{ $item->level_id }}">{{ $item->level_nama }}</option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Filter berdasarkan Level</small>
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
    function modalAction(url) {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    $(document).ready(function () {
        $(document).on('click', '.btn-modal', function () {
            var url = $(this).data('url');
            modalAction(url);
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var datatable = $('#table_user').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ url('user/list') }}",
                type: "POST",
                data: function (d) {
                    d.level_id = $('#level_id').val();
                }
            },
            autoWidth: true,
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'username' },
                { data: 'nama' },
                { data: 'level' },
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        let detailUrl = `{{ url('user/') }}/${row.user_id}`;
                        let editUrl = `{{ url('user') }}/${row.user_id}/edit`;
                        let deleteForm = `
                            <form action="{{ url('user/') }}/${row.user_id}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>`;

                        return `
                            <div class="btn-group-aksi">
                                <a href="${detailUrl}" class="btn btn-sm btn-info">Detail</a>
                                <a href="${editUrl}" class="btn btn-sm btn-warning">Edit</a>
                                ${deleteForm}
                            </div>`;
                    }

                }
            ]
        });

        $('#level_id').on('change', function () {
            datatable.ajax.reload();
        });
    });
</script>
@endpush
