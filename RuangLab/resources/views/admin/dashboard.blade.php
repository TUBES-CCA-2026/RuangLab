@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:54px;height:54px;">
                    <i class="bi bi-building fs-4 text-primary"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['total_lab'] }}</h3>
                    <small class="text-secondary">Total Laboratorium</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width:54px;height:54px;">
                    <i class="bi bi-toggle-on fs-4 text-success"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['lab_aktif'] }}</h3>
                    <small class="text-secondary">Lab Aktif</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center" style="width:54px;height:54px;">
                    <i class="bi bi-hourglass-split fs-4 text-warning"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['reservasi_pending'] }}</h3>
                    <small class="text-secondary">Menunggu Persetujuan</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center" style="width:54px;height:54px;">
                    <i class="bi bi-people fs-4 text-info"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $stats['total_user'] }}</h3>
                    <small class="text-secondary">Total Pengguna</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card table-card">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-semibold mb-0">Reservasi Terbaru</h6>
            <a href="{{ route('admin.reservasi.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
        </div>

        @if($reservasiTerbaru->isEmpty())
            <p class="text-secondary small mb-0">Belum ada pengajuan reservasi.</p>
        @else
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Pemohon</th>
                        <th>Laboratorium</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservasiTerbaru as $r)
                    <tr>
                        <td class="fw-semibold">{{ $r->kode_reservasi }}</td>
                        <td>{{ $r->user->nama ?? '-' }}</td>
                        <td>{{ $r->detail->first()->laboratorium->nama_lab ?? '-' }}</td>
                        <td>{{ optional($r->detail->first())->tanggal_pakai ? \Carbon\Carbon::parse($r->detail->first()->tanggal_pakai)->format('d M Y') : '-' }}</td>
                        <td>
                            <span class="badge rounded-pill badge-status-{{ $r->status }} text-white px-3 py-2">
                                {{ ucwords(str_replace('_', ' ', $r->status)) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.reservasi.show', $r->id) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

@endsection
