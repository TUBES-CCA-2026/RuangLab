@extends('layouts.admin') {{-- sesuaikan dengan layout yang kamu pakai --}}

@section('content')
<div class="container">
    <h2>Data Reservasi Terhapus</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" class="mb-3">
        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari kode reservasi..." class="form-control d-inline w-auto">
        <button class="btn btn-secondary">Cari</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kode Reservasi</th>
                <th>Nama User</th>
                <th>Tanggal Pengajuan</th>
                <th>Status</th>
                <th>Dihapus Pada</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservasis as $item)
            <tr>
                <td>{{ $item->kode_reservasi }}</td>
                <td>{{ $item->user->nama ?? '-' }}</td>
                <td>{{ $item->tanggal_pengajuan }}</td>
                <td>{{ $item->status }}</td>
                <td>{{ $item->deleted_at->format('d/m/Y H:i') }}</td>
                <td>
                    {{-- Restore --}}
                    <form action="{{ route('admin.reservasi.restore', $item->id) }}" method="POST" class="d-inline">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-success" onclick="return confirm('Pulihkan reservasi ini?')">Restore</button>
                    </form>
                    {{-- Hapus Permanen --}}
                    <form action="{{ route('admin.reservasi.forceDelete', $item->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus permanen? Data tidak bisa dikembalikan!')">Hapus Permanen</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center">Tidak ada data terhapus</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $reservasis->links() }}
</div>
@endsection