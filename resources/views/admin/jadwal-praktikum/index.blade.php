@extends('layouts.admin')

@section('title', 'Jadwal Praktikum')
@section('page-title', 'Jadwal Praktikum')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <form method="GET" class="d-flex flex-wrap gap-2">
        <select name="id_day" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Hari</option>
            @foreach($hariList as $h)
                <option value="{{ $h->id }}" {{ request('id_day') == $h->id ? 'selected' : '' }}>{{ $h->nama_hari }}</option>
            @endforeach
        </select>
        <select name="id_lab" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Lab</option>
            @foreach($labs as $lab)
                <option value="{{ $lab->id }}" {{ request('id_lab') == $lab->id ? 'selected' : '' }}>{{ $lab->nama_lab }}</option>
            @endforeach
        </select>
    </form>
    <a href="{{ route('admin.jadwal-praktikum.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Jadwal
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Laboratorium</th>
                    <th>Mata Kuliah</th>
                    <th>Dosen</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwals as $jd)
                <tr>
                    <td class="fw-semibold">{{ $jd->hari->nama_hari ?? '-' }}</td>
                    <td>{{ \Illuminate\Support\Str::substr($jd->jam_mulai,0,5) }} – {{ \Illuminate\Support\Str::substr($jd->jam_selesai,0,5) }}</td>
                    <td>{{ $jd->laboratorium->nama_lab ?? '-' }}</td>
                    <td>{{ $jd->mataKuliah->nama_matkul ?? '-' }}</td>
                    <td>{{ $jd->mataKuliah->nama_dosen ?? '-' }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.jadwal-praktikum.edit', $jd->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('admin.jadwal-praktikum.destroy', $jd->id) }}" method="POST" class="d-inline" data-confirm="Yakin ingin menghapus jadwal ini?">
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
                    <td colspan="6" class="text-center text-secondary py-4">Belum ada jadwal praktikum.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
