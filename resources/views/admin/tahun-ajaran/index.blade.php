@extends('layouts.admin')

@section('title', 'Tahun Ajaran')
@section('page-title', 'Kelola Tahun Ajaran')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <p class="text-secondary small mb-0">Periode tahun ajaran &amp; semester dipakai untuk memfilter halaman Rekap.</p>
    <a href="{{ route('admin.tahun-ajaran.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Tahun Ajaran
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Tahun Ajaran</th>
                    <th>Semester</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tahunAjarans as $ta)
                <tr>
                    <td class="fw-semibold">{{ $ta->tahun_ajaran }}</td>
                    <td>{{ ucfirst($ta->semester) }}</td>
                    <td>{{ \Carbon\Carbon::parse($ta->tanggal_mulai)->translatedFormat('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($ta->tanggal_selesai)->translatedFormat('d M Y') }}</td>
                    <td>
                        @if($ta->is_aktif)
                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Aktif</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">-</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.tahun-ajaran.edit', $ta->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('admin.tahun-ajaran.destroy', $ta->id) }}" method="POST" class="d-inline" data-confirm="Yakin ingin menghapus tahun ajaran ini?">
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
                    <td colspan="6" class="text-center text-secondary py-4">Belum ada data tahun ajaran.</td>
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
