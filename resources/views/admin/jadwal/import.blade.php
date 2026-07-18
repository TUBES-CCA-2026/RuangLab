@extends('layouts.admin')

@section('title', 'Import Jadwal Kuliah')
@section('page-title', 'Import Jadwal Kuliah')

@section('content')

<div class="card table-card mb-4">
    <div class="card-body p-4">
        <h6 class="fw-semibold mb-3"><i class="bi bi-1-circle me-1 text-primary"></i>Download Template</h6>
        <p class="text-secondary small mb-3">
            Unduh template Excel, isi datanya, lalu unggah kembali file tersebut di bawah.
            Kolom <code>id_ruangan</code> diisi dengan ID (UUID) laboratorium — lihat daftar ID pada tabel di bawah.
        </p>
        <a href="{{ route('admin.jadwal.template') }}" class="btn btn-outline-primary">
            <i class="bi bi-download"></i> Download Template (.xlsx)
        </a>

        <div class="table-responsive mt-3">
            <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Nama Lab</th><th>ID Laboratorium</th></tr>
                </thead>
                <tbody>
                    @forelse($labs as $lab)
                    <tr>
                        <td>{{ $lab->nama_lab }}</td>
                        <td><code>{{ $lab->id }}</code></td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-center text-secondary py-3">Belum ada laboratorium.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card table-card">
    <div class="card-body p-4">
        <h6 class="fw-semibold mb-3"><i class="bi bi-2-circle me-1 text-primary"></i>Upload &amp; Import</h6>

        @if ($errors->any())
        <div class="alert alert-danger rounded-3">
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.jadwal.doImport') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Tahun Ajaran</label>
                    <select name="id_tahun_ajaran" class="form-select" required>
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @foreach($tahunAjarans as $ta)
                            <option value="{{ $ta->id }}" {{ old('id_tahun_ajaran') == $ta->id ? 'selected' : '' }}>
                                {{ $ta->nama }}{{ $ta->is_aktif ? ' (Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">File (.xlsx atau .csv)</label>
                    <input type="file" name="file" class="form-control" accept=".xlsx,.csv" required>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4" onclick="return confirm('Impor jadwal dari file ini?');">
                    <i class="bi bi-upload"></i> Konfirmasi Import
                </button>
                <a href="{{ route('admin.jadwal-praktikum.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
            </div>
        </form>

        @if (session('import_errors') && count(session('import_errors')))
        <div class="alert alert-warning rounded-3 mt-4">
            <p class="fw-semibold small mb-2">Baris yang gagal diimpor:</p>
            <ul class="mb-0 small">
                @foreach (session('import_errors') as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>

@endsection
