@extends('layouts.aslab')

@section('title', 'Detail Reservasi')
@section('page-title', 'Detail Reservasi')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7">

        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('aslab.reservasi.index') }}">Riwayat Reservasi</a></li>
                <li class="breadcrumb-item active">Detail Reservasi</li>
            </ol>
        </nav>

        <div class="card table-card">
            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h5 class="fw-bold mb-1">Detail Reservasi</h5>
                        <p class="text-secondary small mb-0">
                            Diajukan {{ \Carbon\Carbon::parse($reservasi->tanggal_pengajuan)->translatedFormat('d M Y') }}
                        </p>
                    </div>
                    <div class="d-flex flex-column align-items-end gap-1">
                        @if($reservasi->is_prioritas)
                            <span class="badge-prioritas"><i class="bi bi-star-fill me-1" style="font-size:.55rem;"></i>Prioritas Aslab</span>
                        @endif
                        <span class="badge rounded-pill badge-status-{{ $reservasi->status }} text-white px-3 py-2">
                            {{ ucwords(str_replace('_', ' ', $reservasi->status)) }}
                        </span>
                    </div>
                </div>

                <hr>

                @foreach($reservasi->detail as $d)
                <div class="mb-3">
                    <h6 class="fw-semibold mb-2"><i class="bi bi-building me-1"></i>{{ $d->laboratorium->nama_lab ?? '-' }}</h6>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li class="mb-1"><i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($d->tanggal_pakai)->translatedFormat('l, d F Y') }}</li>
                        <li><i class="bi bi-clock me-1"></i>{{ \Illuminate\Support\Str::substr($d->jam_mulai,0,5) }} – {{ \Illuminate\Support\Str::substr($d->jam_selesai,0,5) }}</li>
                    </ul>
                </div>
                @endforeach

                <hr>

                <p class="mb-0"><span class="fw-semibold">Keperluan:</span> {{ $reservasi->keperluan }}</p>

                @if($reservasi->catatan_admin)
                <div class="mt-3">
                    <span class="fw-semibold small">Catatan Admin:</span>
                    <p class="text-secondary small mb-0">{{ $reservasi->catatan_admin }}</p>
                </div>
                @endif

                @if($reservasi->status === 'disetujui')
                <div class="alert alert-success rounded-3 mt-4">
                    <i class="bi bi-check-circle me-1"></i>
                    Reservasi disetujui! Kode check-in: <strong>{{ $reservasi->kode_checkin }}</strong>
                </div>
                @elseif($reservasi->status === 'sedang_dipakai')
                <div class="alert alert-info rounded-3 mt-4">
                    <i class="bi bi-door-open me-1"></i> Ruangan sedang dipakai.
                </div>
                @endif

                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('aslab.reservasi.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                    @if($reservasi->status === 'pending')
                    <a href="{{ route('aslab.reservasi.edit', $reservasi->id) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('aslab.reservasi.destroy', $reservasi->id) }}"
                          onsubmit="return confirm('Hapus reservasi ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash me-1"></i> Hapus</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
