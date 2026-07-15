@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

<section class="hero-gradient text-white py-5">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
              
                <h1 class="display-5 fw-bold mb-3">Sistem Penjadwalan dan Reservasi Ruangan Laboratorium Berbasis Web</h1>
                <p class="lead text-white-50 mb-4">
                    RuangLab membantu peminjam dan dosen mengajukan, melacak, dan mengelola
                    reservasi laboratorium secara online.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('laboratorium.index') }}" class="btn btn-light btn-lg fw-semibold px-4">
                        <i class="bi bi-search"></i> Jelajahi Laboratorium
                    </a>
                    @auth
                        <a href="{{ route('reservasi.create') }}" class="btn btn-outline-light btn-lg px-4">
                            Ajukan Reservasi
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4">
                            Daftar Sekarang
                        </a>
                    @endauth
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="bg-white bg-opacity-10 border border-white border-opacity-25 rounded-xl p-4 text-center">
                            <i class="bi bi-building fs-1 text-info"></i>
                            <h3 class="fw-bold mt-2 mb-0">{{ $totalLab }}</h3>
                            <small class="text-white-50">Laboratorium Tersedia</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-white bg-opacity-10 border border-white border-opacity-25 rounded-xl p-4 text-center">
                            <i class="bi bi-lightning-charge fs-1 text-info"></i>
                            <h3 class="fw-bold mt-2 mb-0">24/7</h3>
                            <small class="text-white-50">Pengajuan Online</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="bg-white bg-opacity-10 border border-white border-opacity-25 rounded-xl p-4">
                            <i class="bi bi-shield-check fs-3 text-info"></i>
                            <p class="mb-0 mt-2 small text-white-50">
                                Status reservasi dipantau real-time, lengkap dengan kode check-in untuk validasi di lokasi.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Bagaimana Cara Kerjanya?</h2>
            <p class="text-secondary">Tiga langkah sederhana untuk mendapatkan ruang lab yang kamu butuhkan</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="bg-primary-custom bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:72px;height:72px;">
                        <i class="bi bi-search fs-2 text-primary-custom"></i>
                    </div>
                    <h5 class="fw-semibold">1. Cari Laboratorium</h5>
                    <p class="text-secondary small">Lihat daftar lab, fasilitas, dan kapasitas yang tersedia sesuai kebutuhanmu.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="bg-primary-custom bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:72px;height:72px;">
                        <i class="bi bi-calendar-plus fs-2 text-primary-custom"></i>
                    </div>
                    <h5 class="fw-semibold">2. Ajukan Reservasi</h5>
                    <p class="text-secondary small">Pilih tanggal dan jam pemakaian, lalu kirim pengajuan secara online.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="bg-primary-custom bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:72px;height:72px;">
                        <i class="bi bi-qr-code-scan fs-2 text-primary-custom"></i>
                    </div>
                    <h5 class="fw-semibold">3. Check-in & Gunakan</h5>
                    <p class="text-secondary small">Setelah disetujui, gunakan kode check-in untuk validasi langsung di lab.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@if($labUnggulan->isNotEmpty())
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="fw-bold mb-1">Laboratorium Populer</h2>
                <p class="text-secondary mb-0">Beberapa lab yang sering direservasi</p>
            </div>
            <a href="{{ route('laboratorium.index') }}" class="btn btn-outline-primary d-none d-md-inline-block">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="row g-4">
            @foreach($labUnggulan as $lab)
            <div class="col-md-4">
                <div class="card card-lab h-100">
                    @if($lab->image)
                        <img src="{{ asset('storage/' . $lab->image) }}" class="lab-thumb w-100" alt="{{ $lab->nama_lab }}">
                    @else
                        <div class="lab-thumb w-100 d-flex align-items-center justify-content-center">
                            <i class="bi bi-building fs-1 text-primary-custom opacity-50"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title fw-semibold">{{ $lab->nama_lab }}</h5>
                        <p class="text-secondary small mb-3">
                            <i class="bi bi-people"></i> Kapasitas {{ $lab->kapasitas }} orang
                        </p>
                        <a href="{{ route('laboratorium.show', $lab->id) }}" class="btn btn-sm btn-primary w-100">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="mb-4">
            <h2 class="fw-bold mb-1">Jadwal Reservasi 7 Hari ke Depan</h2>
            <p class="text-secondary mb-0">Lab yang sudah direservasi dalam seminggu ke depan</p>
        </div>

        <div class="card table-card">
            <div class="card-body p-4">
                @if($jadwalMendatang->isEmpty())
                    <p class="text-secondary small mb-0">Tidak ada reservasi lab dalam 7 hari ke depan.</p>
                @else
                    @php $grouped = $jadwalMendatang->groupBy('tanggal_pakai'); @endphp
                    <div class="d-flex flex-column gap-3">
                        @foreach($grouped as $tanggal => $jadwals)
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="rounded-circle bg-primary-custom d-flex align-items-center justify-content-center text-white fw-bold"
                                     style="width:32px;height:32px;font-size:.75rem;flex-shrink:0;">
                                    {{ \Carbon\Carbon::parse($tanggal)->format('d') }}
                                </div>
                                <span class="fw-semibold small">
                                    {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d M Y') }}
                                    @if(\Carbon\Carbon::parse($tanggal)->isToday())
                                        <span class="badge bg-success-subtle text-success ms-1" style="font-size:.68rem;">Hari ini</span>
                                    @endif
                                </span>
                            </div>
                            <div class="d-flex flex-column gap-2 ms-4">
                                @foreach($jadwals as $jd)
                                <div class="d-flex align-items-center gap-2 p-2 rounded-3 bg-light">
                                    <div class="text-primary-custom fw-semibold text-nowrap" style="font-size:.8rem;min-width:95px;">
                                        {{ \Illuminate\Support\Str::substr($jd->jam_mulai,0,5) }} – {{ \Illuminate\Support\Str::substr($jd->jam_selesai,0,5) }}
                                    </div>
                                    <div class="fw-semibold" style="font-size:.82rem;">
                                        <i class="bi bi-building me-1 text-secondary"></i>{{ $jd->laboratorium->nama_lab ?? '-' }}
                                    </div>
                                    <div class="ms-auto">
                                        <span class="badge rounded-pill badge-status-{{ $jd->reservasi->status ?? 'pending' }} text-white px-2" style="font-size:.68rem;">
                                            {{ ucwords(str_replace('_', ' ', $jd->reservasi->status ?? '-')) }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container py-4">
        <div class="rounded-xl hero-gradient text-white p-5 text-center">
            <h3 class="fw-bold mb-2">Siap menggunakan Laboratorium kampus?</h3>
            <p class="text-white-50 mb-4">Daftar sekarang dan ajukan reservasi lab pertamamu hari ini.</p>
            @auth
                <a href="{{ route('reservasi.create') }}" class="btn btn-light btn-lg fw-semibold px-4">Ajukan Reservasi</a>
            @else
                <a href="{{ route('register') }}" class="btn btn-light btn-lg fw-semibold px-4">Daftar Sekarang</a>
            @endauth
        </div>
    </div>
</section>

@endsection
