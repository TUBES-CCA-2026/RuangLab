@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h6 class="fw-bold mb-1">Daftar Pengguna</h6>
        <p class="text-secondary small mb-0">Kelola akun pengguna sistem RuangLab</p>
    </div>
    <a href="{{ route('admin.user.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah Pengguna
    </a>
</div>

{{-- Filter --}}
<form method="GET" class="mb-4">
    <div class="row g-2">
        <div class="col-sm-6 col-md-5">
            <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari nama atau email...">
        </div>
        <div class="col-sm-4 col-md-3">
            <select name="role" class="form-select">
                <option value="">Semua Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->nama_role }}" {{ request('role') == $role->nama_role ? 'selected' : '' }}>
                        {{ ucfirst($role->nama_role) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i> Cari</button>
            <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </div>
</form>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="fw-semibold">{{ $user->nama }}</td>
                    <td class="text-secondary small">{{ $user->email }}</td>
                    <td class="text-secondary small">{{ $user->no_telp ?: '-' }}</td>
                    <td>
                        <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-1">
                            {{ ucfirst($user->role?->nama_role ?? '-') }}
                        </span>
                    </td>
                    <td>
                        @if($user->status)
                            <span class="badge bg-success-subtle text-success border border-success-subtle">Aktif</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Nonaktif</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.user.destroy', $user->id) }}" class="d-inline"
                              data-confirm="Hapus pengguna ini?">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-secondary py-4">Tidak ada pengguna ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $users->withQueryString()->links() }}</div>

@endsection
