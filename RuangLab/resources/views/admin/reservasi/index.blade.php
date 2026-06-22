@extends('layouts.admin')

@section('title', 'Reservasi')
@section('page-title', 'Kelola Reservasi')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <form method="GET" class="d-flex flex-wrap gap-2">
        <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari kode reservasi..." style="max-width:220px;">
        <select name="status" class="form-select" style="max-width:180px;" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            @foreach(['pending','disetujui','ditolak','sedang_dipakai','hangus'] as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
        <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
    </form>
</div>

<form method="GET" action="{{ route('admin.reservasi.export') }}" class="mb-3 d-flex gap-2 flex-wrap align-items-end">
    <div>
        <label class="form-label">Dari Tanggal</label>
        <input type="date" name="dari" value="{{ request('dari') }}" class="form-control">
    </div>
    <div>
        <label class="form-label">Sampai Tanggal</label>
        <input type="date" name="sampai" value="{{ request('sampai') }}" class="form-control">
    </div>
    <div>
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="">-- Semua Status --</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            <option value="sedang_dipakai" {{ request('status') == 'sedang_dipakai' ? 'selected' : '' }}>Sedang Dipakai</option>
            <option value="hangus" {{ request('status') == 'hangus' ? 'selected' : '' }}>Hangus</option>
        </select>
    </div>
    <div>
        <button type="submit" class="btn btn-success mt-4">⬇ Export Excel</button>
    </div>
</form>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Pemohon</th>
                    <th>Laboratorium</th>
                    <th>Tanggal Pakai</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservasis as $r)
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
                @empty
                <tr>
                    <td colspan="6" class="text-center text-secondary py-4">Tidak ada data reservasi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $reservasis->links() }}
</div>

@endsection
