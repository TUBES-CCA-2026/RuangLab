@extends('layouts.admin')

@section('title', 'Reservasi')
@section('page-title', 'Kelola Reservasi')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <form method="GET" class="d-flex flex-wrap gap-2">
        <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari nama pemohon..." style="max-width:220px;">
        <select name="status" class="form-select" style="max-width:180px;" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            @foreach(['pending','disetujui','ditolak','sedang_dipakai','hangus'] as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
        <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
    </form>
    <a href="{{ route('admin.reservasi.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Buat Reservasi
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Pemohon</th>
                    <th>Laboratorium</th>
                    <th>Tanggal Pakai</th>
                    <th>Jam</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservasis as $r)
                @php $d = $r->detail->first(); @endphp
                <tr>
                    <td>
                        <span class="fw-semibold">{{ $r->user->nama ?? '-' }}</span>
                        @if($r->is_prioritas)
                            <br><span class="badge rounded-pill text-white px-2 py-1 mt-1"
                                      style="font-size:.65rem;background:linear-gradient(135deg,#f59e0b,#ef4444);">
                                <i class="bi bi-star-fill me-1" style="font-size:.5rem;"></i>Prioritas
                            </span>
                        @endif
                    </td>
                    <td>{{ $d?->laboratorium->nama_lab ?? '-' }}</td>
                    <td>{{ $d?->tanggal_pakai ? \Carbon\Carbon::parse($d->tanggal_pakai)->format('d M Y') : '-' }}</td>
                    <td class="text-secondary small">{{ $d ? \Illuminate\Support\Str::substr($d->jam_mulai,0,5).' – '.\Illuminate\Support\Str::substr($d->jam_selesai,0,5) : '-' }}</td>
                    <td>
                        <span class="badge rounded-pill badge-status-{{ $r->status }} text-white px-3 py-2">
                            {{ ucwords(str_replace('_', ' ', $r->status)) }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.reservasi.show', $r->id) }}" class="btn btn-sm btn-outline-primary me-1">Detail</a>
                        <form method="POST" action="{{ route('admin.reservasi.destroy', $r->id) }}" class="d-inline"
                              onsubmit="return confirm('Hapus reservasi ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
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
