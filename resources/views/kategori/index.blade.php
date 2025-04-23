@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('kategori/create') }}">Tambah</a>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <table class="table table-bordered table-striped table-hover table-sm" id="table_kategori" style="width: 100%;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Kategori</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kategori as $kategori)
                <tr>
                    <td>{{ $kategori->kategori_id }}</td>
                    <td>{{ $kategori->kategori_kode }}</td>
                    <td>{{ $kategori->kategori_nama }}</td>
                    <td>
                        <a href="{{ url('kategori/' . $kategori->kategori_id) }}" class="btn btn-info btn-sm">Detail</a>
                        <a href="{{ url('kategori/' . $kategori->kategori_id . '/edit') }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ url('kategori/' . $kategori->kategori_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
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

        var datatable = $('#table_kategori').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ url('kategori/list') }}",
                "dataType": "json",
                "type": "POST"
            },
            autoWidth: true,
            columns: [
                {
                    data: 'kategori_id',
                    className: "text-center",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "kategori_kode",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "kategori_nama",
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
    });
</script>
@endpush