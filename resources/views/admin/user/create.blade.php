@extends('layouts.admin')

@section('title', 'Tambah Pengguna')
@section('page-title', 'Tambah Pengguna')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">

        @if($errors->any())
            <div class="alert alert-danger rounded-3">
                <ul class="mb-0 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card table-card">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-4">Form Tambah Pengguna</h6>

                <form method="POST" action="{{ route('admin.user.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">No. Telepon</label>
                        <input type="text" name="no_telp" value="{{ old('no_telp') }}" class="form-control" placeholder="Opsional">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Role</label>
                        <select name="id_role" class="form-select" required>
                            <option value="">-- Pilih Role --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('id_role') == $role->id ? 'selected' : '' }}>
                                    {{ ucfirst($role->nama_role) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="Min. 8 karakter">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-person-plus-fill me-1"></i> Buat Akun
                        </button>
                        <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
