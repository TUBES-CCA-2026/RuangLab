@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card border-0 rounded-xl shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-flask fs-1 text-primary-custom"></i>
                            <h3 class="fw-bold mt-2">Buat Akun Baru</h3>
                            <p class="text-secondary small">Daftar untuk mulai mengajukan reservasi laboratorium</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger rounded-3">
                                <ul class="mb-0 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Nama Lengkap</label>
                                <input type="text" name="nama" value="{{ old('nama') }}" class="form-control" placeholder="Nama lengkap" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="nama@email.com" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">No. Telepon (opsional)</label>
                                <input type="text" name="no_telp" value="{{ old('no_telp') }}" class="form-control" placeholder="08xxxxxxxxxx" inputmode="numeric" pattern="[0-9]*" maxlength="13" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label small fw-semibold">Kata Sandi</label>
                                    <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label small fw-semibold">Konfirmasi Sandi</label>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi sandi" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2 mt-2">Daftar</button>
                        </form>

                        <p class="text-center small text-secondary mt-4 mb-0">
                            Sudah punya akun? <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Masuk di sini</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
