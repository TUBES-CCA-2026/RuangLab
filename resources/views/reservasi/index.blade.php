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

        @include('partials.jadwal-praktikum-hari-ini')

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
                                <th>Laboratorium</th>
                                <th>Tanggal Pakai</th>
                                <th>Jam</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservasis as $r)
                            @php $d = $r->detail->first(); @endphp
                            <tr>
                                <td class="fw-semibold">{{ $d?->laboratorium->nama_lab ?? '-' }}</td>
                                <td>{{ $d?->tanggal_pakai ? \Carbon\Carbon::parse($d->tanggal_pakai)->translatedFormat('d M Y') : '-' }}</td>
                                <td class="text-secondary small">{{ $d ? \Illuminate\Support\Str::substr($d->jam_mulai,0,5).' - '.\Illuminate\Support\Str::substr($d->jam_selesai,0,5) : '-' }}</td>
                                <td>
                                    <span class="badge rounded-pill badge-status-{{ $r->status }} text-white px-3 py-2">
                                        {{ ucwords(str_replace('_', ' ', $r->status)) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('reservasi.show', $r->id) }}" class="btn btn-sm btn-outline-primary me-1">Detail</a>
                                   @if(in_array($r->status, ['pending', 'disetujui']))
                                    <a href="{{ route('reservasi.edit', $r->id) }}" class="btn btn-sm btn-outline-secondary me-1">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('reservasi.destroy', $r->id) }}" class="d-inline"
                                          data-confirm="Batalkan reservasi ini?">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                    @endif
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
