@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('barang') }}" class="form-horizontal">
            @csrf

            {{-- Kode Barang --}}
            <div class="form-group row">
                <label for="barang_kode" class="col-md-2 col-form-label">Kode Barang</label>
                <div class="col-md-10">
                    <input type="text" class="form-control" id="barang_kode" name="barang_kode" value="{{ old('barang_kode') }}" placeholder="Masukkan Kode Barang" required>
                    @error('barang_kode')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            {{-- Nama Barang --}}
            <div class="form-group row">
                <label for="barang_nama" class="col-md-2 col-form-label">Nama Barang</label>
                <div class="col-md-10">
                    <input type="text" class="form-control" id="barang_nama" name="barang_nama" value="{{ old('barang_nama') }}" placeholder="Masukkan Nama Barang" required>
                    @error('barang_nama')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            {{-- Kategori --}}
            <div class="form-group row">
                <label for="kategori_id" class="col-md-2 col-form-label">Kategori</label>
                <div class="col-md-10">
                    <select class="form-control" id="kategori_id" name="kategori_id" required>
                        <option value="">Pilih Kategori --</option>
                        @foreach($kategori as $item)
                        <option value="{{ $item->kategori_id }}" {{ old('kategori_id') == $item->kategori_id ? 'selected' : '' }}>
                        {{ $item->kategori_nama }}</option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>


            {{-- Harga Beli --}}
            <div class="form-group row">
                <label for="harga_beli" class="col-md-2 col-form-label">Harga Beli</label>
                <div class="col-md-10">
                    <input type="number" class="form-control" id="harga_beli" name="harga_beli" value="{{ old('harga_beli') }}" placeholder="Masukkan Harga Beli" step="0.01" required>
                    @error('harga_beli')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            {{-- Harga Jual --}}
            <div class="form-group row">
                <label for="harga_jual" class="col-md-2 col-form-label">Harga Jual</label>
                <div class="col-md-10">
                    <input type="number" class="form-control" id="harga_jual" name="harga_jual" value="{{ old('harga_jual') }}" placeholder="Masukkan Harga Jual" step="0.01" required>
                    @error('harga_jual')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            {{-- Tombol --}}
            <div class="form-group row">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ url('barang') }}" class="btn btn-secondary">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
