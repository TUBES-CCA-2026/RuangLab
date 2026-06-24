@extends('layouts.app')

@section('content')
<div class="container">
    <h2>History Peminjaman Saya</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kode Reservasi</th>
                <th>Ruangan</th>
                <th>Tanggal Pakai</th>
                <th>Jam</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservasis as $item)
            @php $detail = $item->detail->first(); @endphp
            <tr>
                <td>{{ $item->kode_reservasi }}</td>
                <td>{{ $detail->laboratorium->nama_lab ?? '-' }}</td>
                <td>{{ $detail->tanggal_pakai ?? '-' }}</td>
                <td>{{ $detail->jam_mulai ?? '-' }} - {{ $detail->jam_selesai ?? '-' }}</td>
                <td>{{ ucfirst($item->status) }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center">Belum ada history peminjaman</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $reservasis->links() }}
</div>
@endsection