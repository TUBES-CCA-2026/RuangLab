@extends('layouts.admin')

@section('title', 'Tahun Ajaran')
@section('page-title', 'Kelola Tahun Ajaran')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <form method="GET" class="d-flex" style="max-width: 320px;">
        <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari tahun ajaran...">
        <button class="btn btn-outline-secondary ms-2" type="submit"><i class="bi bi-search"></i></button>
    </form>
    <a href="{{ route('admin.tahun-ajaran.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Tahun Ajaran
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Semester</th>
                    <th>Periode</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tahunAjarans as $ta)
                <tr>
                    <td class="fw-semibold">{{ $ta->nama }}</td>
                    <td>{{ ucfirst($ta->semester) }}</td>
                    <td>{{ $ta->tanggal_mulai->format('d/m/Y') }} &ndash; {{ $ta->tanggal_selesai->format('d/m/Y') }}</td>
                    <td>
                        @if($ta->is_aktif)
                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Aktif</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">Nonaktif</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @unless($ta->is_aktif)
                        <form action="{{ route('admin.tahun-ajaran.aktifkan', $ta->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Aktifkan tahun ajaran ini? Tahun ajaran lain akan dinonaktifkan.');">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-check-circle"></i> Aktifkan
                            </button>
                        </form>
                        @endunless
                        <a href="{{ route('admin.tahun-ajaran.edit', $ta->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('admin.tahun-ajaran.destroy', $ta->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus tahun ajaran ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-secondary py-4">Belum ada data tahun ajaran.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $tahunAjarans->links() }}
</div>

@endsection
