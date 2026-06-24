@csrf
@if(isset($lab))
    @method('PUT')
@endif

@if ($errors->any())
    <div class="alert alert-danger rounded-3">
        <ul class="mb-0 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label small fw-semibold">Nama Laboratorium</label>
        <input type="text" name="nama_lab" value="{{ old('nama_lab', $lab->nama_lab ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label small fw-semibold">Kapasitas</label>
        <input type="number" name="kapasitas" value="{{ old('kapasitas', $lab->kapasitas ?? '') }}" class="form-control" min="1" required>
    </div>

    <div class="col-md-6">
        <label class="form-label small fw-semibold">Penanggung Jawab</label>
        <select name="penanggung_jawab" class="form-select" required>
            <option value="">-- Pilih Penanggung Jawab --</option>
            @foreach($penanggungJawabs as $pj)
                <option value="{{ $pj->id }}" {{ old('penanggung_jawab', $lab->penanggung_jawab ?? '') == $pj->id ? 'selected' : '' }}>
                    {{ $pj->nama }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Status</label>
        <select name="status" class="form-select" required>
            <option value="1" {{ old('status', $lab->status ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ old('status', $lab->status ?? 1) == 0 ? 'selected' : '' }}>Nonaktif</option>
        </select>
    </div>

    <div class="col-12">
        <label class="form-label small fw-semibold">Fasilitas</label>
        <textarea name="fasilitas" rows="3" class="form-control" placeholder="Contoh: 20 unit PC, proyektor, AC, whiteboard">{{ old('fasilitas', $lab->fasilitas ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <label class="form-label small fw-semibold">Foto Utama Laboratorium</label>
        @if(isset($lab) && $lab->image)
            <div class="mb-2">
                <img src="{{ asset('storage/' . $lab->image) }}" alt="{{ $lab->nama_lab }}" style="height:100px;border-radius:10px;">
            </div>
        @endif
        <input type="file" name="image" class="form-control" accept="image/*">
        <small class="text-secondary">Format JPG/PNG, maks 2MB. Kosongkan jika tidak ingin mengubah foto utama.</small>
    </div>

    <div class="col-12">
        <label class="form-label small fw-semibold">Foto Tambahan <span class="text-secondary fw-normal">(bisa lebih dari 1)</span></label>

        @if(isset($lab) && $lab->images->count())
            <div class="d-flex flex-wrap gap-2 mb-2">
                @foreach($lab->images as $img)
                <div class="position-relative">
                    <img src="{{ asset('storage/' . $img->image_path) }}" style="height:80px;width:80px;object-fit:cover;border-radius:8px;">
                    <div class="form-check position-absolute top-0 end-0 m-1">
                        <input class="form-check-input" type="checkbox" name="hapus_images[]" value="{{ $img->id }}"
                               id="hapus_{{ $img->id }}" style="background:white;">
                        <label class="form-check-label" for="hapus_{{ $img->id }}" style="font-size:.65rem;color:red;">Hapus</label>
                    </div>
                </div>
                @endforeach
            </div>
            <small class="text-secondary d-block mb-2">Centang "Hapus" di atas foto yang ingin dihapus.</small>
        @endif

        <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
        <small class="text-secondary">Pilih beberapa file sekaligus. Format JPG/PNG, maks 2MB per file.</small>
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary px-4">
        <i class="bi bi-check-lg"></i> Simpan
    </button>
    <a href="{{ route('admin.laboratorium.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
</div>
