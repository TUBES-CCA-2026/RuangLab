@extends('layouts.app')

@section('title', 'Detail Reservasi')

@section('content')
<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('reservasi.index') }}">Reservasi Saya</a></li>
                <li class="breadcrumb-item active">{{ $reservasi->kode_reservasi }}</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card border-0 rounded-xl shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h4 class="fw-bold mb-1">{{ $reservasi->kode_reservasi }}</h4>
                                <p class="text-secondary small mb-0">Diajukan {{ \Carbon\Carbon::parse($reservasi->tanggal_pengajuan)->translatedFormat('d M Y') }}</p>
                            </div>
                            <span class="badge rounded-pill badge-status-{{ $reservasi->status }} text-white px-3 py-2">
                                {{ ucwords(str_replace('_', ' ', $reservasi->status)) }}
                            </span>
                        </div>

                        <hr>

                        @foreach($reservasi->detail as $d)
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-2"><i class="bi bi-building"></i> {{ $d->laboratorium->nama_lab ?? '-' }}</h6>
                            <ul class="list-unstyled small text-secondary mb-0">
                                <li class="mb-1"><i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::parse($d->tanggal_pakai)->translatedFormat('d F Y') }}</li>
                                <li><i class="bi bi-clock me-1"></i> {{ \Illuminate\Support\Str::substr($d->jam_mulai,0,5) }} - {{ \Illuminate\Support\Str::substr($d->jam_selesai,0,5) }}</li>
                            </ul>
                        </div>
                        @endforeach

                        <hr>

                        <p class="mb-1"><span class="fw-semibold">Keperluan:</span> {{ $reservasi->keperluan }}</p>

                        @if($reservasi->status === 'disetujui')
                        <div class="alert alert-success rounded-3 mt-3">
                            <i class="bi bi-check-circle"></i> Reservasi disetujui! Gunakan kode check-in berikut saat tiba di lokasi:
                            <div class="fw-bold fs-5 mt-2">{{ $reservasi->kode_checkin }}</div>
                        </div>
                        @elseif($reservasi->status === 'ditolak')
                        <div class="alert alert-danger rounded-3 mt-3">
                            <i class="bi bi-x-circle"></i> Reservasi ditolak.
                        </div>
                        @elseif($reservasi->status === 'pending')
                        <div class="alert alert-warning rounded-3 mt-3">
                            <i class="bi bi-hourglass-split"></i> Menunggu persetujuan admin.
                        </div>
                        @endif

                        @if($reservasi->catatan_admin)
                        <div class="mt-3">
                            <span class="fw-semibold small">Catatan Admin:</span>
                            <p class="text-secondary small mb-0">{{ $reservasi->catatan_admin }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
