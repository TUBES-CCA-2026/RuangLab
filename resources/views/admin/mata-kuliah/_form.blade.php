@csrf
@if(isset($matkul))
    @method('PUT')
@endif

<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label small fw-semibold">Nama Mata Kuliah</label>
        <input type="text" name="nama_matkul" value="{{ old('nama_matkul', $matkul->nama_matkul ?? '') }}"
               class="form-control" placeholder="Contoh: Basis Data" required>
    </div>
    <div class="col-md-4">
        <label class="form-label small fw-semibold">SKS</label>
        <input type="number" name="sks" value="{{ old('sks', $matkul->sks ?? 2) }}"
               class="form-control" min="1" max="10" required>
    </div>
    <div class="col-md-8">
        <label class="form-label small fw-semibold">Dosen</label>
        <input type="text" name="nama_dosen" value="{{ old('nama_dosen', $matkul->nama_dosen ?? '') }}"
               class="form-control" placeholder="Contoh: Pak Agus" required>
    </div>
    <div class="col-12">
        <label class="form-label small fw-semibold">Catatan <span class="text-secondary">(opsional)</span></label>
        <textarea name="catatan_admin" rows="2" class="form-control">{{ old('catatan_admin', $matkul->catatan_admin ?? '') }}</textarea>
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Simpan</button>
    <a href="{{ route('admin.mata-kuliah.index') }}" class="btn btn-outline-secondary">Batal</a>
</div>
