@extends('layouts.template')

@section('content')
<div class="section">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ url('kategori') }}" class="form-horizontal">
                @csrf
                <div class="form-group row">
                    <label for="kategori_kode" class="col-md-2 col-form-label">Kode Kategori</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" id="kategori_kode" name="kategori_kode" value="{{ old('kategori_kode') }}" required>
                        @error('kategori_kode')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="kategori_nama" class="col-md-2 col-form-label">Nama Kategori</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" id="kategori_nama" name="kategori_nama" value="{{ old('kategori_nama') }}" required>
                        @error('kategori_nama')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ url('kategori') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('css')
@endpush

@push('js')
@endpush