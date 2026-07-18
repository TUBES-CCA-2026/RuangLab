@extends('layouts.admin')

@section('title', 'Mata Kuliah')
@section('page-title', 'Kelola Mata Kuliah')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <form method="GET" class="d-flex" style="max-width: 320px;">
        <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari mata kuliah...">
        <button class="btn btn-outline-secondary ms-2" type="submit"><i class="bi bi-search"></i></button>
    </form>
    <a href="{{ route('admin.mata-kuliah.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Mata Kuliah
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama Mata Kuliah</th>
                    <th>Dosen</th>
                    <th>SKS</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($matkuls as $matkul)
                <tr>
                    <td class="fw-semibold">{{ $matkul->nama_matkul }}</td>
                    <td>{{ $matkul->nama_dosen }}</td>
                    <td>{{ $matkul->sks }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.mata-kuliah.edit', $matkul->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('admin.mata-kuliah.destroy', $matkul->id) }}" method="POST" class="d-inline" data-confirm="Yakin ingin menghapus mata kuliah ini?">
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
                    <td colspan="4" class="text-center text-secondary py-4">Belum ada data mata kuliah.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $matkuls->links() }}
</div>

@endsection
