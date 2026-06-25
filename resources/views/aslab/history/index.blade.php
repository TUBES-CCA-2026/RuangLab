<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>History Reservasi — Aslab</title>
</head>
<body>

<h1>History Reservasi</h1>

<table border="1" width="100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Peminjam</th>
            <th>Laboratorium</th>
            <th>Status</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @forelse($reservasis as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->user->nama ?? '-' }}</td>
           <td>{{ $item->detail->first()->laboratorium->nama_lab ?? '-' }}</td>
            <td>{{ $item->status }}</td>
            <td>{{ $item->created_at->format('d M Y') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="5" align="center">Belum ada history</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $reservasis->links() }}

</body>
</html>