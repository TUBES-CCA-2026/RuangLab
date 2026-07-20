@extends('layouts.admin')

@section('title', 'Jadwal Praktikum')
@section('page-title', 'Jadwal Praktikum')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <form method="GET" class="d-flex flex-wrap gap-2">
        @if($lihatTerhapus)
            <input type="hidden" name="terhapus" value="1">
        @endif
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
        <select name="id_tahun_ajaran" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Tahun Ajaran</option>
            @foreach($tahunAjarans as $ta)
                <option value="{{ $ta->id }}" {{ $tahunAjaranTerpilih == $ta->id ? 'selected' : '' }}>{{ $ta->nama }}{{ $ta->is_aktif ? ' (Aktif)' : '' }}</option>
            @endforeach
        </select>
    </form>
    <div class="d-flex gap-2">
        @if($lihatTerhapus)
            <a href="{{ route('admin.jadwal-praktikum.index', request()->except('terhapus')) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Jadwal Aktif
            </a>
        @else
            <a href="{{ route('admin.jadwal-praktikum.index', array_merge(request()->query(), ['terhapus' => 1])) }}" class="btn btn-outline-secondary">
                <i class="bi bi-trash3"></i> Jadwal Terhapus
            </a>
            <a href="{{ route('admin.jadwal-praktikum.export', request()->query()) }}" class="btn btn-success">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export Excel
            </a>
            <a href="{{ route('admin.jadwal.import') }}" class="btn btn-outline-primary">
                <i class="bi bi-file-earmark-arrow-up me-1"></i> Import Jadwal
            </a>
            <a href="{{ route('admin.jadwal-praktikum.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Tambah Jadwal
            </a>
        @endif
    </div>
</div>

@if($lihatTerhapus)
<div class="alert alert-secondary small rounded-3">
    <i class="bi bi-info-circle me-1"></i> Menampilkan jadwal yang sudah dihapus. Jadwal masih tersimpan dan bisa dikembalikan kapan saja.
</div>
@endif

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
                    <th>Tahun Ajaran</th>
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
                    <td>{{ $jd->tahunAjaran->nama ?? '-' }}</td>
                    <td class="text-end">
                        @if($lihatTerhapus)
                            <form action="{{ route('admin.jadwal-praktikum.restore', $jd->id) }}" method="POST" class="d-inline"
                                  data-confirm="Kembalikan jadwal ini?" data-confirm-variant="success" data-confirm-icon="bi-arrow-counterclockwise" data-confirm-label="Ya, Kembalikan">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-arrow-counterclockwise"></i> Pulihkan
                                </button>
                            </form>
                            <form action="{{ route('admin.jadwal-praktikum.forceDelete', $jd->id) }}" method="POST" class="d-inline"
                                  data-confirm="Hapus permanen? Data tidak bisa dikembalikan!" data-confirm-label="Ya, Hapus Permanen">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </form>
                        @else
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
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-secondary py-4">
                        {{ $lihatTerhapus ? 'Tidak ada jadwal yang terhapus.' : 'Belum ada jadwal praktikum.' }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
