@extends('layouts.aslab')

@section('title', 'Riwayat Reservasi')
@section('page-title', 'Riwayat Reservasi Saya')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-secondary small mb-0">Semua reservasi yang pernah kamu ajukan</p>
    </div>
    <a href="{{ route('aslab.reservasi.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Reservasi Baru
    </a>
</div>

@if($reservasis->isEmpty())
    <div class="card table-card">
        <div class="card-body text-center py-5">
            <i class="bi bi-calendar-x text-secondary" style="font-size:2.5rem;"></i>
            <p class="text-secondary mt-2 mb-3">Belum ada reservasi.</p>
            <a href="{{ route('aslab.reservasi.create') }}" class="btn btn-primary">Buat Reservasi Pertama</a>
        </div>
    </div>
@else
    <div class="card table-card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kode Reservasi</th>
                        <th>Laboratorium</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservasis as $r)
                    @php $d = $r->detail->first(); @endphp
                    <tr>
                        <td>
                            <span class="fw-semibold">{{ $r->kode_reservasi }}</span>
                            @if($r->is_prioritas)
                                <br><span class="badge-prioritas"><i class="bi bi-star-fill me-1" style="font-size:.5rem;"></i>Prioritas</span>
                            @endif
                        </td>
                        <td>{{ $d->laboratorium->nama_lab ?? '-' }}</td>
                        <td>{{ $d ? \Carbon\Carbon::parse($d->tanggal_pakai)->translatedFormat('d M Y') : '-' }}</td>
                        <td class="text-nowrap">
                            @if($d)
                                {{ \Illuminate\Support\Str::substr($d->jam_mulai,0,5) }} – {{ \Illuminate\Support\Str::substr($d->jam_selesai,0,5) }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <span class="badge rounded-pill badge-status-{{ $r->status }} text-white px-3 py-2">
                                {{ ucwords(str_replace('_', ' ', $r->status)) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('aslab.reservasi.show', $r->id) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $reservasis->links() }}
    </div>
@endif

@endsection
