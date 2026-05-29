@extends('layouts.base')

@section('page-title', 'Update Departemen')
@section('page-subtitle', 'Kelola data departemen perusahaan')

@section('page-actions')
    <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">Kembali</a>
@endsection

@section('content')
    {{-- konten halaman --}}

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <span class="fw-semibold" style="font-size:13.5px">
                        <i class="fa-solid fa-building me-2" style="color:#1B5E3B"></i>
                        Informasi Departemen
                    </span>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.departments.update', $department->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Nama Departemen --}}
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">
                                Nama Departemen <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                id="name"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ $department->name }}"
                                placeholder="Contoh: Engineering, Marketing, Finance..."
                                autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
    
                        {{-- Manager --}}
                        <div class="form-group mb-4">
                            <label for="manager_id" class="form-label">Manager</label>
                            <select id="manager_id"
                                    name="manager_id"
                                    class="form-select @error('manager_id') is-invalid @enderror">
                                <option value="">-- Pilih Manager (opsional) --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('manager_id') == $user->id || $department->manager_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('manager_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text" style="font-size:11.5px">
                                Manager dapat diubah sewaktu-waktu.
                            </div>
                        </div>
    
                        {{-- Actions --}}
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i>
                                Simpan Departemen
                            </button>
                            <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection