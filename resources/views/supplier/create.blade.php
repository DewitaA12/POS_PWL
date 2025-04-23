@extends('layouts.template')

@section('content')
<div class="section">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ url('supplier') }}" class="form-horizontal">
                @csrf
                <div class="form-group row">
                    <label for="supplier_kode" class="col-md-2 col-form-label">Kode Supplier</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" id="supplier_kode" name="supplier_kode" value="{{ old('supplier_kode') }}" required>
                        @error('supplier_kode')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="supplier_nama" class="col-md-2 col-form-label">Nama Supplier</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" id="supplier_nama" name="supplier_nama" value="{{ old('supplier_nama') }}" required>
                        @error('supplier_nama')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="alamat" class="col-md-2 col-form-label">Alamat</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" id="alamat" name="alamat" value="{{ old('alamat') }}" required>
                        @error('alamat')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ url('supplier') }}" class="btn btn-secondary">Batal</a>
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
