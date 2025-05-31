@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('kategori/create') }}">Tambah</a>
            <button class="btn btn-sm btn-success mt-1 btn-modal" data-url="{{ url('kategori/create_ajax') }}">Tambah Ajax</button>
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

<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" 
    data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
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
    function modalAction(url = ''){
            $('#myModal').load(url,function(){
            $('#myModal').modal('show');
            });
        }
        var dataUser;
        $(document).ready(function(){
            dataUser = $('#table_kategori').DataTable({
                // serverSide: true, jika ingin menggunakan server side processing
                serverSide: true,
                ajax: {
                    "url": "{{ url('kategori/list') }}",
                    "dataType": "json",
                    "type": "POST",

                },

                columns: [
                    {
                        // nomor urut dari laravel datatable addIndexColumn()
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },{
                        data: "kategori_kode",
                        className: "",
                        // orderable true, jika ingin kolom ini bisa diurutkan
                        orderable: true,
                        // searchable true, jika ingin kolom ini bisa dicari
                        searchable: true
                    },{
                        data: "kategori_nama",
                        className: "",
                        orderable: true,
                        searchable: true
                    },{
                        data: "aksi",
                        className: "",
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

        });
</script>
@endpush