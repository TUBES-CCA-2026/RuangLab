<?php echo csrf_field(); ?>
<?php if(isset($tahunAjaran)): ?>
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
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Nama Tahun Ajaran</label>
        <input type="text" name="nama" value="<?php echo e(old('nama', $tahunAjaran->nama ?? '')); ?>" class="form-control" placeholder="Contoh: 2025/2026 Ganjil" required>
    </div>
    <div class="col-md-3">
        <label class="form-label small fw-semibold">Tahun Ajaran</label>
        <input type="text" name="tahun_ajaran" value="<?php echo e(old('tahun_ajaran', $tahunAjaran->tahun_ajaran ?? '')); ?>" class="form-control" placeholder="Contoh: 2025/2026" required>
    </div>
    <div class="col-md-3">
        <label class="form-label small fw-semibold">Semester</label>
        <select name="semester" class="form-select" required>
            <option value="">-- Pilih Semester --</option>
            <option value="ganjil" <?php echo e(old('semester', $tahunAjaran->semester ?? '') == 'ganjil' ? 'selected' : ''); ?>>Ganjil</option>
            <option value="genap" <?php echo e(old('semester', $tahunAjaran->semester ?? '') == 'genap' ? 'selected' : ''); ?>>Genap</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label small fw-semibold">Tanggal Mulai</label>
        <input type="date" name="tanggal_mulai" value="<?php echo e(old('tanggal_mulai', isset($tahunAjaran) ? $tahunAjaran->tanggal_mulai->format('Y-m-d') : '')); ?>" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Tanggal Selesai</label>
        <input type="date" name="tanggal_selesai" value="<?php echo e(old('tanggal_selesai', isset($tahunAjaran) ? $tahunAjaran->tanggal_selesai->format('Y-m-d') : '')); ?>" class="form-control" required>
    </div>

    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_aktif" value="1" id="is_aktif"
                   <?php echo e(old('is_aktif', $tahunAjaran->is_aktif ?? false) ? 'checked' : ''); ?>>
            <label class="form-check-label small" for="is_aktif">
                Jadikan tahun ajaran aktif <span class="text-secondary">(otomatis menonaktifkan tahun ajaran lain)</span>
            </label>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary px-4">
        <i class="bi bi-check-lg"></i> Simpan
    </button>
    <a href="<?php echo e(route('admin.tahun-ajaran.index')); ?>" class="btn btn-outline-secondary px-4">Batal</a>
</div>
<?php /**PATH C:\xampp\htdocs\RuangLab\resources\views/admin/tahun-ajaran/_form.blade.php ENDPATH**/ ?>