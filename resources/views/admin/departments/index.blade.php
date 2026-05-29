@extends('layouts.base')

@section('page-title', 'Departemen')
@section('page-subtitle', 'Kelola data departemen perusahaan')

@section('page-actions')
    <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">+ Tambah Departemen</a>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Departemen</li>
@endsection

@section('content')
    {{-- konten halaman --}}

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <span class="fw-semibold" style="font-size:13.5px">Daftar Departemen</span>
    
            {{-- Search --}}
            <div class="d-flex align-items-center gap-2">
                <form method="GET" action="{{ route('admin.departments.index') }}" id="searchForm">
                    {{-- Pertahankan sort & dir saat search --}}
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                        <input type="hidden" name="dir" value="{{ request('dir') }}">
                    @endif

                    <input type="text"
                        name="search"
                        id="searchInput"
                        class="form-control form-control-sm"
                        placeholder="Cari departemen..."
                        value="{{ request('search') }}"
                        style="width: 200px">
                </form>
                <!-- <input type="text" id="searchInput" class="form-control form-control-sm"
                    placeholder="Cari departemen..." style="width: 200px"> -->
                <span class="text-muted" style="font-size:12px" id="rowCount"></span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0" id="departmentTable">
                @php
                    $sort = request('sort');
                    $dir  = request('dir', 'desc');
                    $nextDir = $dir === 'asc' ? 'desc' : 'asc';
                @endphp
                <thead>
                    <tr>
                        <th style="width:50px">#</th>

                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'dir' => $sort === 'name' ? $nextDir : 'asc']) }}" class="text-decoration-none text-dark">
                                Nama Departemen
                                <i class="fa-solid fa-sort{{ $sort === 'name' ? ($dir === 'asc' ? '-up' : '-down') : '' }}"
                                style="{{ $sort !== 'name' ? 'opacity:0.3' : '' }}"></i>
                            </a>
                        </th>

                        <th>Manager</th>

                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'users_count', 'dir' => $sort === 'users_count' ? $nextDir : 'asc']) }}" class="text-decoration-none text-dark">
                                Jumlah Karyawan
                                <i class="fa-solid fa-sort{{ $sort === 'users_count' ? ($dir === 'asc' ? '-up' : '-down') : '' }}"
                                style="{{ $sort !== 'users_count' ? 'opacity:0.3' : '' }}"></i>
                            </a>
                        </th>

                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'dir' => $sort === 'created_at' ? $nextDir : 'asc']) }}" class="text-decoration-none text-dark">
                                Dibuat
                                <i class="fa-solid fa-sort{{ $sort === 'created_at' ? ($dir === 'asc' ? '-up' : '-down') : '' }}"
                                style="{{ $sort !== 'created_at' ? 'opacity:0.3' : '' }}"></i>
                            </a>
                        </th>

                        <th style="width:120px">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($departments as $index => $department)
                    <tr>
                        <td class="text-muted">{{ $index + 1 }}</td>
                        <td><span class="fw-medium">{{ $department->name }}</span></td>
                        <td>
                            @if($department->manager)
                                <span class="badge bg-info">{{ $department->manager->name }}</span>
                            @else
                                <span class="text-muted fst-italic" style="font-size:12px">Tidak ada</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                {{ $department->users_count ?? 0 }} karyawan
                            </span>
                        </td>
                        <td class="text-muted" style="font-size:12px">
                            {{ $department->created_at->format('d M Y') }}
                        </td>
                        <!-- Edit / Delete -->
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.departments.edit', $department) }}"
                                class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger btn-delete"
                                        title="Hapus"
                                        data-id="{{ $department->id }}"
                                        data-name="{{ $department->name }}"
                                        data-action="{{ route('admin.departments.destroy', $department) }}">
                                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="6" class="text-center py-5">
                            <div style="max-width: 280px; margin: 0 auto;">
                                {{-- Icon --}}
                                <div style="width:64px;height:64px;border-radius:16px;background:#F5F4F0;display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                                    <i class="fa-solid fa-building" style="font-size:28px;color:#C8C5BE"></i>
                                </div>

                                {{-- Text --}}
                                <h6 class="fw-semibold mb-1" style="color:#1A1917">Belum ada departemen</h6>
                                <p class="text-muted mb-3" style="font-size:13px;line-height:1.5">
                                    Mulai dengan menambahkan departemen pertama untuk mengorganisir karyawan.
                                </p>

                                {{-- CTA --}}
                                <a href="{{ route('admin.departments.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-plus me-1"></i>
                                    Tambah Departemen
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($departments->hasPages())
        <div class="card-footer d-flex align-items-center justify-content-between" style="background:#FAFAF8;border-top:1px solid #E2E0DB;padding:12px 20px">
            <span class="text-muted" style="font-size:12px">
                Menampilkan {{ $departments->firstItem() }}-{{ $departments->lastItem() }} dari {{ $departments->total() }} departemen
            </span>
            {{ $departments->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width:400px">
            <div class="modal-content" style="border-radius:12px;border:1px solid #E2E0DB">
                <div class="modal-body p-4 text-center">
                    <div style="width:52px;height:52px;border-radius:50%;background:#FEE2E2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#C0392B" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <h6 class="fw-semibold mb-1">Hapus Departemen?</h6>
                    <p class="text-muted mb-4" style="font-size:13px">
                        Kamu yakin ingin menghapus departemen <strong id="deleteName"></strong>? Tindakan ini tidak dapat dibatalkan.
                    </p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    @vite(['resources/css/pages/departments.css'])
@endpush

@push('scripts')
    @vite(['resources/js/pages/departments.js'])
@endpush