@extends('layouts.admin')

@section('title', 'History Reservasi')
@section('page-title', 'History Reservasi')

@section('content')

<div class="card table-card mb-4">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('admin.history.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Cari Nama</label>
                <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Nama peminjam...">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="disetujui"      {{ request('status')==='disetujui'      ?'selected':'' }}>Disetujui</option>
                    <option value="ditolak"        {{ request('status')==='ditolak'        ?'selected':'' }}>Ditolak</option>
                    <option value="sedang_dipakai" {{ request('status')==='sedang_dipakai' ?'selected':'' }}>Sedang Dipakai</option>
                    <option value="hangus"         {{ request('status')==='hangus'         ?'selected':'' }}>Hangus</option>
                    <option value="deleted" {{ request('status')==='deleted' ?'selected':'' }}>Dihapus (Soft Delete)</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Laboratorium</label>
                <select name="lab" class="form-select">
                    <option value="">Semua Lab</option>
                    @foreach($labs as $lab)
                        <option value="{{ $lab->id }}" {{ request('lab')===$lab->id ?'selected':'' }}>{{ $lab->nama_lab }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ request('dari') }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ request('sampai') }}" class="form-control">
            </div>
            <div class="col-md-1 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
                <a href="{{ route('admin.history.index') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card table-card">
    <div class="card-body p-0">
        <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
            <h6 class="fw-semibold mb-0">
                <i class="bi bi-clock-history me-2"></i>Riwayat Reservasi
                <span class="badge bg-secondary ms-1">{{ $reservasis->total() }}</span>
            </h6>
            <a href="{{ route('admin.history.export', request()->query()) }}" class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export Excel
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="small text-secondary">
                        <th class="ps-4">No</th>
                        <th>Peminjam</th>
                        <th>Laboratorium</th>
                        <th>Tanggal Pakai</th>
                        <th>Jam</th>
                        <th>Keperluan</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservasis as $r)
                    @php $detail = $r->detail->first(); @endphp
                    <tr>
                        <td class="ps-4 text-secondary small">{{ $reservasis->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="fw-medium">{{ $r->user->nama ?? '-' }}</div>
                            <div class="small text-secondary">{{ $r->user->email ?? '' }}</div>
                        </td>
                        <td>{{ $detail->laboratorium->nama_lab ?? '-' }}</td>
                        <td class="small">{{ $detail ? \Carbon\Carbon::parse($detail->tanggal_pakai)->translatedFormat('d M Y') : '-' }}</td>
                        <td class="small">{{ $detail ? substr($detail->jam_mulai,0,5).' - '.substr($detail->jam_selesai,0,5) : '-' }}</td>
                        <td class="small text-secondary" style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $r->keperluan }}</td>
                        <td>
                            @if($r->is_prioritas)
                                <span class="badge badge-prioritas">Prioritas</span>
                            @else
                                <span class="badge bg-light text-secondary">Umum</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge rounded-pill badge-status-{{ $r->status }} text-white px-2 py-1 small">
                                {{ ucwords(str_replace('_',' ',$r->status)) }}
                            </span>
                        </td>
                        <td class="d-flex gap-1">
    <a href="{{ route('admin.reservasi.show', $r->id) }}" 
       class="btn btn-sm btn-outline-primary">
        <i class="bi bi-eye"></i>
    </a>

    @if($r->deleted_at)
    {{-- Tombol Restore --}}
    <form method="POST" action="{{ route('admin.history.restore', $r->id) }}"
          data-confirm="Kembalikan reservasi ini?" data-confirm-variant="success" data-confirm-icon="bi-arrow-counterclockwise" data-confirm-label="Ya, Kembalikan">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-success" title="Kembalikan">
            <i class="bi bi-arrow-counterclockwise"></i>
        </button>
    </form>

    {{-- Tombol Hapus Permanen --}}
    <form method="POST" action="{{ route('admin.history.forceDelete', $r->id) }}"
          data-confirm="Hapus permanen? Data tidak bisa dikembalikan!" data-confirm-label="Ya, Hapus Permanen">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Permanen">
            <i class="bi bi-trash3-fill"></i>
        </button>
    </form>
    @endif
</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-secondary py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i> Belum ada riwayat reservasi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reservasis->hasPages())
        <div class="px-4 py-3 border-top">{{ $reservasis->links() }}</div>
        @endif
    </div>
</div>

@endsection