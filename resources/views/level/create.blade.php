@extends('layouts.template')

@section('content')
<div class="section">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title}}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
        <form method="POST" action="{{ url('level') }}" class="form-horizontal">
                @csrf
                <div class="form-group row">
                    <label for="level_id">Level ID</label>
                    <input type="text" class="form-control" id="level_id" name="level_id" value="{{ old('level_id')}}" required>
                    @error('level_id')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group row">
                    <label for="level_kode">Level Kode</label>
                    <input type="text" class="form-control" id="level_kode" name="level_kode" value="{{ old('level_kode')}}" required>
                    @error('level_kode')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group row">
                    <label for="level_nama">Level Nama</label>
                    <input type="text" class="form-control" id="level_nama" name="level_nama" value="{{ old('level_nama')}}" required>
                    @error('level_nama')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group row">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ url('level') }}" class="btn btn-secondary">Batal</a>
                </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection