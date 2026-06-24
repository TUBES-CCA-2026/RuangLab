@php
    $layout = auth()->user()->isAdmin() ? 'layouts.admin' : (auth()->user()->isAslab() ? 'layouts.aslab' : 'layouts.app');
@endphp

@extends($layout)

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3">
                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                         style="width:70px;height:70px;flex-shrink:0;">
                        <i class="bi bi-person-fill fs-2 text-primary"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1">{{ $user->nama }}</h4>
                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-1">
                            {{ $user->role?->nama_role ?? 'Pengguna' }}
                        </span>
                    </div>
                </div>

                <hr>

                <dl class="row small mb-0">
                    <dt class="col-sm-4 text-secondary fw-semibold">Nama Lengkap</dt>
                    <dd class="col-sm-8">{{ $user->nama }}</dd>

                    <dt class="col-sm-4 text-secondary fw-semibold">Email</dt>
                    <dd class="col-sm-8">{{ $user->email }}</dd>

                    <dt class="col-sm-4 text-secondary fw-semibold">No. Telepon</dt>
                    <dd class="col-sm-8">{{ $user->no_telp ?: '-' }}</dd>

                    <dt class="col-sm-4 text-secondary fw-semibold">Role</dt>
                    <dd class="col-sm-8">{{ ucfirst($user->role?->nama_role ?? '-') }}</dd>

                    <dt class="col-sm-4 text-secondary fw-semibold">Status Akun</dt>
                    <dd class="col-sm-8">
                        @if($user->status)
                            <span class="badge bg-success-subtle text-success border border-success-subtle">Aktif</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Tidak Aktif</span>
                        @endif
                    </dd>
                </dl>

                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                        <i class="bi bi-pencil-fill me-1"></i> Edit Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
