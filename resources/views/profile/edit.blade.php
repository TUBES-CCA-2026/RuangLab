@php
    $layout = auth()->user()->isAdmin() ? 'layouts.admin' : (auth()->user()->isAslab() ? 'layouts.aslab' : 'layouts.app');
@endphp

@extends($layout)

@section('title', 'Edit Profil')
@section('page-title', 'Edit Profil')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">

        @if ($errors->any())
            <div class="alert alert-danger rounded-3">
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Edit Profil</h5>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">No. Telepon</label>
                        <input type="text" name="no_telp" value="{{ old('no_telp', $user->no_telp) }}" class="form-control" placeholder="Opsional" inputmode="numeric" pattern="[0-9]*" maxlength="13" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                    </div>

                    <hr>
                    <p class="small text-secondary">Kosongkan password jika tidak ingin mengubahnya.</p>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
