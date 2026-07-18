@extends('layouts.admin')

@section('title', 'Laboratorium')
@section('page-title', 'Kelola Laboratorium')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <form method="GET" class="d-flex" style="max-width: 320px;">
        <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari laboratorium...">
        <button class="btn btn-outline-secondary ms-2" type="submit"><i class="bi bi-search"></i></button>
    </form>
    <a href="{{ route('admin.laboratorium.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Laboratorium
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama Lab</th>
                    <th>Penanggung Jawab</th>
                    <th>Kapasitas</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($labs as $lab)
                <tr>
                    <td class="fw-semibold">{{ $lab->nama_lab }}</td>
                    <td>{{ $lab->penanggungJawab->nama ?? '-' }}</td>
                    <td>{{ $lab->kapasitas }} orang</td>
                    <td>
                        @if($lab->status)
                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Aktif</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">Nonaktif</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.laboratorium.edit', $lab->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('admin.laboratorium.destroy', $lab->id) }}" method="POST" class="d-inline" data-confirm="Yakin ingin menghapus laboratorium ini?">
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
                    <td colspan="5" class="text-center text-secondary py-4">Belum ada data laboratorium.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $labs->links() }}
</div>

@endsection
