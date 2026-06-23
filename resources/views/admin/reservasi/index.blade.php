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
                    <td>
                        <span class="fw-semibold">{{ $r->kode_reservasi }}</span>
                        @if($r->is_prioritas)
                            <br><span class="badge rounded-pill text-white px-2 py-1 mt-1"
                                      style="font-size:.65rem;background:linear-gradient(135deg,#f59e0b,#ef4444);">
                                <i class="bi bi-star-fill me-1" style="font-size:.5rem;"></i>Prioritas
                            </span>
                        @endif
                    </td>
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
