@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('barang/create') }}">Tambah</a>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
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
            <tbody>
                @foreach ($barang as $barang)
                <tr>
                    <td>{{ $barang->barang_id }}</td>
                    <td>{{ $barang->barang_kode }}</td>
                    <td>{{ $barang->barang_nama }}</td>
                    <td>{{ number_format($barang->harga_beli, 2) }}</td>
                    <td>{{ number_format($barang->harga_jual, 2) }}</td>
                    <td>{{ $barang->kategori->kategori_nama ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ url('barang/' . $barang->barang_id) }}" class="btn btn-info btn-sm">Detail</a>
                        <a href="{{ url('barang/' . $barang->barang_id . '/edit') }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ url('barang/' . $barang->barang_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">Hapus</button>
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

        var datatable = $('#table_barang').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ url('barang/list') }}",
                "dataType": "json",
                "type": "POST"
            },
            autoWidth: true,
            columns: [
                {
                    data: 'barang_id',
                    className: "text-center",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "barang_kode",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "barang_nama",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "harga_beli",
                    orderable: true,
                    searchable: true,
                    render: $.fn.dataTable.render.number(',', '.', 2, 'Rp ')
                },
                {
                    data: "harga_jual",
                    orderable: true,
                    searchable: true,
                    render: $.fn.dataTable.render.number(',', '.', 2, 'Rp ')
                },
                {
                    data: "kategori_nama", // Accessing the category nama
                    orderable: true,
                    searchable: true
                },
                {
                    data: "aksi",
                    orderable: false,
                    searchable: false
                },
            ]
        });
    });
</script>
@endpush