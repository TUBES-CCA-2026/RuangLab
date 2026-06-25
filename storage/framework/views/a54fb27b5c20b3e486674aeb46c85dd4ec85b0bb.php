<?php echo csrf_field(); ?>
<?php if(isset($lab)): ?>
    <?php echo method_field('PUT'); ?>
<?php endif; ?>

<?php if($errors->any()): ?>
    <div class="alert alert-danger rounded-3">
        <ul class="mb-0 small">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label small fw-semibold">Nama Laboratorium</label>
        <input type="text" name="nama_lab" value="<?php echo e(old('nama_lab', $lab->nama_lab ?? '')); ?>" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label small fw-semibold">Kapasitas</label>
        <input type="number" name="kapasitas" value="<?php echo e(old('kapasitas', $lab->kapasitas ?? '')); ?>" class="form-control" min="1" required>
    </div>

    <div class="col-md-6">
        <label class="form-label small fw-semibold">Penanggung Jawab</label>
        <select name="penanggung_jawab" class="form-select" required>
            <option value="">-- Pilih Penanggung Jawab --</option>
            <?php $__currentLoopData = $penanggungJawabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($pj->id); ?>" <?php echo e(old('penanggung_jawab', $lab->penanggung_jawab ?? '') == $pj->id ? 'selected' : ''); ?>>
                    <?php echo e($pj->nama); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Status</label>
        <select name="status" class="form-select" required>
            <option value="1" <?php echo e(old('status', $lab->status ?? 1) == 1 ? 'selected' : ''); ?>>Aktif</option>
            <option value="0" <?php echo e(old('status', $lab->status ?? 1) == 0 ? 'selected' : ''); ?>>Nonaktif</option>
        </select>
    </div>

    <div class="col-12">
        <label class="form-label small fw-semibold">Fasilitas</label>
        <textarea name="fasilitas" rows="3" class="form-control" placeholder="Contoh: 20 unit PC, proyektor, AC, whiteboard"><?php echo e(old('fasilitas', $lab->fasilitas ?? '')); ?></textarea>
    </div>

    <div class="col-12">
        <label class="form-label small fw-semibold">Foto Utama Laboratorium</label>
        <?php if(isset($lab) && $lab->image): ?>
            <div class="mb-2">
                <img src="<?php echo e(asset('storage/' . $lab->image)); ?>" alt="<?php echo e($lab->nama_lab); ?>" style="height:100px;border-radius:10px;">
            </div>
        <?php endif; ?>
        <input type="file" name="image" class="form-control" accept="image/*">
        <small class="text-secondary">Format JPG/PNG, maks 2MB. Kosongkan jika tidak ingin mengubah foto utama.</small>
    </div>

    <div class="col-12">
        <label class="form-label small fw-semibold">
            Foto Tambahan
            <span class="text-secondary fw-normal">(maksimal 10 foto)</span>
        </label>

        <?php $jumlahFotoAda = isset($lab) ? $lab->images->count() : 0; ?>

        <?php if($jumlahFotoAda > 0): ?>
            <div class="d-flex flex-wrap gap-2 mb-2">
                <?php $__currentLoopData = $lab->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="position-relative">
                    <img src="<?php echo e(asset('storage/' . $img->image_path)); ?>"
                         style="height:80px;width:80px;object-fit:cover;border-radius:8px;">
                    <div class="form-check position-absolute top-0 end-0 m-1">
                        <input class="form-check-input" type="checkbox"
                               name="hapus_images[]" value="<?php echo e($img->id); ?>"
                               id="hapus_<?php echo e($img->id); ?>"
                               style="background:white;"
                               onchange="hitungSisa()">
                        <label class="form-check-label" for="hapus_<?php echo e($img->id); ?>"
                               style="font-size:.65rem;color:red;">Hapus</label>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <small class="text-secondary d-block mb-2">
                Foto tersimpan: <strong id="labelFotoAda"><?php echo e($jumlahFotoAda); ?></strong>/10.
                Centang "Hapus" pada foto yang ingin dihapus.
            </small>
        <?php endif; ?>

        <input type="file" name="images[]" id="inputFotoTambahan"
               class="form-control" accept="image/*" multiple>
        <small class="text-secondary">
            Pilih hingga <strong>10 foto</strong> sekaligus. Format JPG/PNG, maks 2MB per foto.
        </small>

        
        <div id="infoSisa" class="small mt-1 text-primary" style="display:none;"></div>

        
        <div id="previewFoto" class="d-flex flex-wrap gap-2 mt-2"></div>
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary px-4">
        <i class="bi bi-check-lg"></i> Simpan
    </button>
    <a href="<?php echo e(route('admin.laboratorium.index')); ?>" class="btn btn-outline-secondary px-4">Batal</a>
</div>

<script>
(function () {
    const MAKS          = 10;
    const inputFoto     = document.getElementById('inputFotoTambahan');
    const previewWrap   = document.getElementById('previewFoto');
    const infoSisa      = document.getElementById('infoSisa');
    const checkboxHapus = document.querySelectorAll('input[name="hapus_images[]"]');
    const labelFotoAda  = document.getElementById('labelFotoAda');
    let jumlahAda       = <?php echo e($jumlahFotoAda); ?>;

    // Hitung berapa foto yang sedang dicentang untuk dihapus
    function jumlahDihapus() {
        let n = 0;
        checkboxHapus.forEach(c => { if (c.checked) n++; });
        return n;
    }

    // Update label jumlah foto tersimpan (visual saja)
    window.hitungSisa = function () {
        const sisa = jumlahAda - jumlahDihapus();
        if (labelFotoAda) labelFotoAda.textContent = sisa;
        tampilInfoSisa(sisa, inputFoto.files ? inputFoto.files.length : 0);
    };

    function tampilInfoSisa(ada, baru) {
        const total = ada + baru;
        if (baru > 0 || ada < jumlahAda) {
            infoSisa.style.display = 'block';
            if (total > MAKS) {
                infoSisa.textContent = `⚠ Total foto akan menjadi ${total}, melebihi batas ${MAKS}.`;
                infoSisa.className = 'small mt-1 text-danger';
            } else {
                infoSisa.textContent = `Total foto setelah disimpan: ${total}/${MAKS}.`;
                infoSisa.className = 'small mt-1 text-primary';
            }
        } else {
            infoSisa.style.display = 'none';
        }
    }

    inputFoto.addEventListener('change', function () {
        previewWrap.innerHTML = '';

        const ada  = jumlahAda - jumlahDihapus();
        const slot = MAKS - ada;

        if (this.files.length > MAKS) {
            alert(`Maksimal ${MAKS} foto yang bisa dipilih sekaligus.`);
            this.value = '';
            tampilInfoSisa(ada, 0);
            return;
        }

        if (this.files.length > slot) {
            alert(`Sisa slot foto: ${slot}. Hapus beberapa foto yang ada atau kurangi foto baru.`);
            this.value = '';
            tampilInfoSisa(ada, 0);
            return;
        }

        tampilInfoSisa(ada, this.files.length);

        // Tampilkan preview
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const wrap = document.createElement('div');
                wrap.style = 'position:relative;display:inline-block;';

                const img = document.createElement('img');
                img.src   = e.target.result;
                img.style = 'height:80px;width:80px;object-fit:cover;border-radius:8px;border:2px solid #dee2e6;';
                img.title = file.name;

                const label = document.createElement('div');
                label.style = 'font-size:.65rem;text-align:center;color:#6c757d;max-width:80px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;';
                label.textContent = file.name;

                wrap.appendChild(img);
                wrap.appendChild(label);
                previewWrap.appendChild(wrap);
            };
            reader.readAsDataURL(file);
        });
    });
})();
</script>
<?php /**PATH D:\RuangLab\resources\views/admin/laboratorium/_form.blade.php ENDPATH**/ ?>