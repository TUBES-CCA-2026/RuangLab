<?php $__env->startSection('title', 'Import Jadwal Kuliah'); ?>
<?php $__env->startSection('page-title', 'Import Jadwal Kuliah'); ?>

<?php $__env->startSection('content'); ?>

<div class="card table-card mb-4">
    <div class="card-body p-4">
        <h6 class="fw-semibold mb-3"><i class="bi bi-1-circle me-1 text-primary"></i>Download Template</h6>
        <p class="text-secondary small mb-3">
            Unduh template Excel, isi datanya, lalu unggah kembali file tersebut di bawah.
            Kolom <code>id_ruangan</code> diisi dengan ID (UUID) laboratorium — lihat daftar ID pada tabel di bawah.
        </p>
        <a href="<?php echo e(route('admin.jadwal.template')); ?>" class="btn btn-outline-primary">
            <i class="bi bi-download"></i> Download Template (.xlsx)
        </a>

        <div class="table-responsive mt-3">
            <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Nama Lab</th><th>ID Laboratorium</th></tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $labs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($lab->nama_lab); ?></td>
                        <td><code><?php echo e($lab->id); ?></code></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="2" class="text-center text-secondary py-3">Belum ada laboratorium.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card table-card">
    <div class="card-body p-4">
        <h6 class="fw-semibold mb-3"><i class="bi bi-2-circle me-1 text-primary"></i>Upload &amp; Import</h6>

        <?php if($errors->any()): ?>
        <div class="alert alert-danger rounded-3">
            <ul class="mb-0 small">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('admin.jadwal.doImport')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Tahun Ajaran</label>
                    <select name="id_tahun_ajaran" class="form-select" required>
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        <?php $__currentLoopData = $tahunAjarans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($ta->id); ?>" <?php echo e(old('id_tahun_ajaran') == $ta->id ? 'selected' : ''); ?>>
                                <?php echo e($ta->nama); ?><?php echo e($ta->is_aktif ? ' (Aktif)' : ''); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                <a href="<?php echo e(route('admin.jadwal-praktikum.index')); ?>" class="btn btn-outline-secondary px-4">Batal</a>
            </div>
        </form>

        <?php if(session('import_errors') && count(session('import_errors'))): ?>
        <div class="alert alert-warning rounded-3 mt-4">
            <p class="fw-semibold small mb-2">Baris yang gagal diimpor:</p>
            <ul class="mb-0 small">
                <?php $__currentLoopData = session('import_errors'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($err); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\RuangLab\resources\views/admin/jadwal/import.blade.php ENDPATH**/ ?>