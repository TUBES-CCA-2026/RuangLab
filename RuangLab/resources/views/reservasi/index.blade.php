@extends('layouts.app')

@section('title', 'Reservasi Saya')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1">Reservasi Saya</h2>
                <p class="text-secondary mb-0">Pantau status pengajuan reservasi laboratoriummu</p>
            </div>
            <a href="{{ route('reservasi.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Ajukan Reservasi Baru
            </a>
        </div>

        @if($reservasis->isEmpty())
            <div class="text-center py-5 bg-white rounded-xl">
                <i class="bi bi-calendar-x fs-1 text-secondary"></i>
                <p class="text-secondary mt-2 mb-3">Kamu belum memiliki riwayat reservasi.</p>
                <a href="{{ route('reservasi.create') }}" class="btn btn-primary">Ajukan Reservasi Pertamamu</a>
            </div>
        @else
            <div class="card border-0 rounded-xl shadow-sm">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                 <th>Kode Reservasi</th>
                                <th>Laboratorium</th>
                                <th>Tanggal Pakai</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservasis as $r)
                            <tr>
                                <td class="fw-semibold">{{ $r->kode_reservasi }}</td>
                                <td>{{ $r->detail->first()->laboratorium->nama_lab ?? '-' }}</td>
                                <td>{{ optional($r->detail->first())->tanggal_pakai ? \Carbon\Carbon::parse($r->detail->first()->tanggal_pakai)->translatedFormat('d M Y') : '-' }}</td>
                                <td>
                                    <span class="badge rounded-pill badge-status-{{ $r->status }} text-white px-3 py-2">
                                        {{ ucwords(str_replace('_', ' ', $r->status)) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('reservasi.show', $r->id) }}" class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
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
    </div>
</section>
@endsection
