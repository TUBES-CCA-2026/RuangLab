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
        <label class="form-label small fw-semibold">Foto Tambahan <span class="text-secondary fw-normal">(bisa lebih dari 1)</span></label>

        <?php if(isset($lab) && $lab->images->count()): ?>
            <div class="d-flex flex-wrap gap-2 mb-2">
                <?php $__currentLoopData = $lab->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="position-relative">
                    <img src="<?php echo e(asset('storage/' . $img->image_path)); ?>" style="height:80px;width:80px;object-fit:cover;border-radius:8px;">
                    <div class="form-check position-absolute top-0 end-0 m-1">
                        <input class="form-check-input" type="checkbox" name="hapus_images[]" value="<?php echo e($img->id); ?>"
                               id="hapus_<?php echo e($img->id); ?>" style="background:white;">
                        <label class="form-check-label" for="hapus_<?php echo e($img->id); ?>" style="font-size:.65rem;color:red;">Hapus</label>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <small class="text-secondary d-block mb-2">Centang "Hapus" di atas foto yang ingin dihapus.</small>
        <?php endif; ?>

        <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
        <small class="text-secondary">Pilih beberapa file sekaligus. Format JPG/PNG, maks 2MB per file.</small>
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary px-4">
        <i class="bi bi-check-lg"></i> Simpan
    </button>
    <a href="<?php echo e(route('admin.laboratorium.index')); ?>" class="btn btn-outline-secondary px-4">Batal</a>
</div>
<?php /**PATH C:\xampp\htdocs\RuangLab\resources\views/admin/laboratorium/_form.blade.php ENDPATH**/ ?>