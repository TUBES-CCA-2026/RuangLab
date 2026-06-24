@extends('layouts.aslab')

@section('content')
<div class="container">
    <h2>History Semua Reservasi</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                
                <th>Nama Peminjam</th>
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
                
                <td>{{ $item->user->nama ?? '-' }}</td>
                <td>{{ $detail->laboratorium->nama_lab ?? '-' }}</td>
                <td>{{ $detail->tanggal_pakai ?? '-' }}</td>
                <td>{{ $detail->jam_mulai ?? '-' }} - {{ $detail->jam_selesai ?? '-' }}</td>
                <td>{{ ucfirst($item->status) }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center">Belum ada history reservasi</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $reservasis->links() }}
</div>
@endsection