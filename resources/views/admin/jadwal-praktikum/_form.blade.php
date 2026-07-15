@csrf

@if($errors->any())
    <div class="alert alert-danger rounded-3">
        <ul class="mb-0 small">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Nama Mata Kuliah / Praktikum</label>
        <input type="text" name="nama_matkul" value="{{ old('nama_matkul', $jadwal->mataKuliah->nama_matkul ?? '') }}" class="form-control" placeholder="Praktikum Basis Data" required>
    </div>
    <div class="col-md-4">
        <label class="form-label small fw-semibold">Dosen Pengampu</label>
        <input type="text" name="nama_dosen" value="{{ old('nama_dosen', $jadwal->mataKuliah->nama_dosen ?? '') }}" class="form-control" placeholder="Nama dosen" required>
    </div>
    <div class="col-md-2">
        <label class="form-label small fw-semibold">SKS</label>
        <input type="number" name="sks" value="{{ old('sks', $jadwal->mataKuliah->sks ?? 1) }}" class="form-control" min="1" max="10">
    </div>

    <div class="col-md-4">
        <label class="form-label small fw-semibold">Laboratorium</label>
        <select name="id_lab" class="form-select" required>
            <option value="">Pilih Lab</option>
            @foreach($labs as $lab)
                <option value="{{ $lab->id }}" {{ old('id_lab', $jadwal->id_lab ?? '') == $lab->id ? 'selected' : '' }}>{{ $lab->nama_lab }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label small fw-semibold">Hari</label>
        <select name="id_day" class="form-select" required>
            <option value="">Pilih Hari</option>
            @foreach($hariList as $h)
                <option value="{{ $h->id }}" {{ old('id_day', $jadwal->id_day ?? '') == $h->id ? 'selected' : '' }}>{{ $h->nama_hari }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label small fw-semibold">Jam Mulai</label>
        <input type="time" name="jam_mulai" value="{{ old('jam_mulai', isset($jadwal) ? \Illuminate\Support\Str::substr($jadwal->jam_mulai,0,5) : '') }}" class="form-control" required>
    </div>
    <div class="col-md-2">
        <label class="form-label small fw-semibold">Jam Selesai</label>
        <input type="time" name="jam_selesai" value="{{ old('jam_selesai', isset($jadwal) ? \Illuminate\Support\Str::substr($jadwal->jam_selesai,0,5) : '') }}" class="form-control" required>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('admin.jadwal-praktikum.index') }}" class="btn btn-outline-secondary">Batal</a>
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-lg"></i> Simpan
    </button>
</div>
