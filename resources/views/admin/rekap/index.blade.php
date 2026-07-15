@extends('layouts.admin')

@section('title', 'Rekap Tahun Ajaran')
@section('page-title', 'Rekap Tahun Ajaran')

@section('content')

<div class="card table-card mb-4">
    <div class="card-body p-4">
        <form method="GET" class="d-flex flex-wrap align-items-end gap-2">
            <div>
                <label class="form-label small fw-semibold mb-1">Tahun Ajaran</label>
                <select name="tahun_ajaran" class="form-select" onchange="this.form.submit()">
                    @forelse($tahunAjarans as $ta)
                        <option value="{{ $ta->id }}" {{ optional($tahunAjaran)->id == $ta->id ? 'selected' : '' }}>
                            {{ $ta->nama }}{{ $ta->is_aktif ? ' (Aktif)' : '' }}
                        </option>
                    @empty
                        <option value="">Belum ada tahun ajaran</option>
                    @endforelse
                </select>
            </div>
        </form>
    </div>
</div>

@if(!$tahunAjaran)
    <div class="alert alert-warning rounded-3">
        Belum ada data tahun ajaran. Silakan tambahkan tahun ajaran terlebih dahulu di menu
        <a href="{{ route('admin.tahun-ajaran.index') }}">Tahun Ajaran</a>.
    </div>
@else

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:54px;height:54px;">
                    <i class="bi bi-calendar-check fs-4 text-primary"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $totalReservasi }}</h3>
                    <small class="text-secondary">Total Reservasi</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width:54px;height:54px;">
                    <i class="bi bi-check-circle fs-4 text-success"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $perStatus['disetujui'] ?? 0 }}</h3>
                    <small class="text-secondary">Disetujui</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center" style="width:54px;height:54px;">
                    <i class="bi bi-x-circle fs-4 text-danger"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $perStatus['ditolak'] ?? 0 }}</h3>
                    <small class="text-secondary">Ditolak</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:54px;height:54px;">
                    <i class="bi bi-slash-circle fs-4 text-secondary"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $totalHangus }}</h3>
                    <small class="text-secondary">Hangus (No-show)</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="card table-card h-100">
            <div class="card-body p-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-bar-chart-line me-1 text-primary"></i>
                    Reservasi per Bulan
                    <span class="text-secondary fw-normal small ms-1">— {{ $tahunAjaran->nama }}</span>
                </h6>
                <canvas id="chartPerBulan" height="120"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card table-card h-100">
            <div class="card-body p-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-pie-chart me-1 text-primary"></i>
                    Reservasi per Status
                </h6>
                <div class="d-flex flex-column gap-2">
                    @foreach($perStatus as $status => $jumlah)
                        <div class="d-flex justify-content-between align-items-center p-2 rounded-3 bg-light">
                            <span class="badge rounded-pill badge-status-{{ $status }} text-white px-2 py-1" style="font-size:.72rem;">
                                {{ ucwords(str_replace('_', ' ', $status)) }}
                            </span>
                            <span class="fw-semibold">{{ $jumlah }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card table-card h-100">
            <div class="card-body p-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-building me-1 text-primary"></i>
                    Laboratorium Paling Sering Digunakan
                </h6>
                @if($topLabs->isEmpty())
                    <p class="text-secondary small mb-0">Belum ada data.</p>
                @else
                    <ol class="mb-0 ps-3">
                        @foreach($topLabs as $tl)
                            <li class="mb-2 d-flex justify-content-between">
                                <span>{{ $tl->laboratorium->nama_lab ?? '-' }}</span>
                                <span class="fw-semibold">{{ $tl->total }}x</span>
                            </li>
                        @endforeach
                    </ol>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card table-card h-100">
            <div class="card-body p-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-person-check me-1 text-primary"></i>
                    Peminjam Paling Aktif
                </h6>
                @if($topPeminjam->isEmpty())
                    <p class="text-secondary small mb-0">Belum ada data.</p>
                @else
                    <ol class="mb-0 ps-3">
                        @foreach($topPeminjam as $tp)
                            <li class="mb-2 d-flex justify-content-between">
                                <span>{{ $tp->user->nama ?? '-' }}</span>
                                <span class="fw-semibold">{{ $tp->total }}x</span>
                            </li>
                        @endforeach
                    </ol>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('chartPerBulan'), {
        type: 'bar',
        data: {
            labels: @json($perBulan->pluck('label')),
            datasets: [{
                label: 'Jumlah Reservasi',
                data: @json($perBulan->pluck('total')),
                backgroundColor: '#2952e3',
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
</script>
@endpush

@endif

@endsection
