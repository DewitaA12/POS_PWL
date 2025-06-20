@extends('layouts.template')

@section('content')
<div class="container mt-5">
    {{-- Success Notification --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" fill="currentColor">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </svg>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Profile Section --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                {{-- Photo Column (Left) --}}
                <div class="col-md-4 text-start">
                    <div class="position-relative d-inline-block">
                        <img src="{{ asset(Auth::user()->foto ?? 'uploads/foto_user/default.jpg') }}"
                             alt="Foto Profil"
                             class="img-thumbnail rounded-circle"
                             style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #e9ecef;">
                        <button class="btn btn-primary btn-sm position-absolute"
                                style="bottom: -10px; right: -10px; z-index: 1000; border-radius: 50%; padding: 8px;"
                                data-bs-toggle="modal" 
                                data-bs-target="#modalEditFoto"
                                title="Ubah Foto Profil">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- User Identity Column (Right) --}}
                <div class="col-md-8">
                    <div class="mb-3 d-flex align-items-center">
                        <label for="username" class="form-label fw-bold me-3" style="width: 100px;">Username</label>
                        <input type="text" 
                               class="form-control bg-light" 
                               id="username" 
                               value="{{ Auth::user()->username }}" 
                               readonly
                               style="border-radius: 8px; max-width: 400px;">
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <label for="nama" class="form-label fw-bold me-3" style="width: 100px;">Nama</label>
                        <input type="text" 
                               class="form-control bg-light" 
                               id="nama" 
                               value="{{ Auth::user()->nama }}" 
                               readonly
                               style="border-radius: 8px; max-width: 400px;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit Foto --}}
    <div class="modal fade" id="modalEditFoto" tabindex="-1" aria-labelledby="editFotoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('updateFoto') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header border-0 pb-2">
                        <h5 class="modal-title" id="editFotoLabel">Ubah Foto Profil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-2">
                        <div class="mb-3 d-flex align-items-center">
                            <label for="foto" class="form-label me-3" style="width: 100px;">Pilih Foto</label>
                            <input type="file" 
                                   class="form-control" 
                                   id="foto" 
                                   name="foto" 
                                   accept="image/*" 
                                   required
                                   style="border-radius: 8px; max-width: 400px;">
                            @error('foto')
                                <small class="text-danger ms-3">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="text-muted small ms-3">
                            Maksimum ukuran file: 2MB. Format yang didukung: JPG, PNG.
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection