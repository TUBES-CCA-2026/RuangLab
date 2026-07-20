@extends('layouts.admin')

@section('title', 'Rekap Laboratorium')
@section('page-title', 'Rekap Penggunaan Laboratorium')

@section('content')

<div class="card table-card mb-4">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('admin.rekap.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Tahun Ajaran</label>
                <select name="tahun_ajaran_id" class="form-select">
                    <option value="">Semua Periode</option>
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id }}" {{ $tahunAjaranTerpilih === $ta->id ? 'selected' : '' }}>
                            {{ $ta->nama }}{{ $ta->is_aktif ? ' (Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Laboratorium</label>
                <select name="id_lab" class="form-select">
                    <option value="">Semua Lab</option>
                    @foreach($labs as $lab)
                        <option value="{{ $lab->id }}" {{ request('id_lab') === $lab->id ? 'selected' : '' }}>{{ $lab->nama_lab }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Mata Kuliah</label>
                <select name="id_matkul" class="form-select">
                    <option value="">Semua Mata Kuliah</option>
                    @foreach($matkuls as $matkul)
                        <option value="{{ $matkul->id }}" {{ request('id_matkul') === $matkul->id ? 'selected' : '' }}>{{ $matkul->nama_matkul }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Terapkan</button>
                <a href="{{ route('admin.rekap.index') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mb-4">
    <a href="{{ route('admin.rekap.exportPdf', request()->query()) }}" class="btn btn-outline-danger btn-sm">
        <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
    </a>
    <a href="{{ route('admin.rekap.exportExcel', request()->query()) }}" class="btn btn-success btn-sm">
        <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export Excel
    </a>
</div>

<div class="row g-4">
    <div class="col-sm-6 col-xl-4">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:54px;height:54px;">
                    <i class="bi bi-journal-bookmark fs-4 text-primary"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $data['jumlah_praktikum'] }}</h3>
                    <small class="text-secondary">Jumlah Praktikum</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center" style="width:54px;height:54px;">
                    <i class="bi bi-building-check fs-4 text-info"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $data['jumlah_penggunaan_lab'] }}</h3>
                    <small class="text-secondary">Jumlah Penggunaan Laboratorium</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width:54px;height:54px;">
                    <i class="bi bi-calendar-check fs-4 text-success"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $data['jumlah_reservasi'] }}</h3>
                    <small class="text-secondary">Jumlah Reservasi</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center" style="width:54px;height:54px;">
                    <i class="bi bi-x-circle fs-4 text-danger"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $data['jumlah_pembatalan'] }}</h3>
                    <small class="text-secondary">Jumlah Pembatalan</small>
                </div>
            </div>
        </div>
        @if($data['ada_filter_lab_atau_matkul'])
        <div class="form-text px-1">Reservasi yang dibatalkan sendiri oleh peminjam tidak ikut dihitung saat filter Lab/Mata Kuliah aktif (detailnya tidak tersimpan).</div>
        @endif
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center" style="width:54px;height:54px;">
                    <i class="bi bi-clock-history fs-4 text-warning"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $data['jam_penggunaan_lab'] }} <small class="fs-6 fw-normal">jam</small></h3>
                    <small class="text-secondary">Jam Penggunaan Laboratorium</small>
                </div>
            </div>
        </div>
    </div>
</div>

<p class="text-secondary small mt-4">
    <i class="bi bi-info-circle me-1"></i>
    "Jumlah Praktikum" diambil dari data Jadwal Praktikum (jadwal tetap mingguan) sehingga tidak berubah oleh filter Tahun Ajaran/Semester.
</p>

@endsection
