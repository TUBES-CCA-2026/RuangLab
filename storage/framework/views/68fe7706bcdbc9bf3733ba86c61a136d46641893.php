

<?php $__env->startSection('title', 'Buat Reservasi'); ?>
<?php $__env->startSection('page-title', 'Buat Reservasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-7">

        <?php if($errors->any()): ?>
            <div class="alert alert-danger rounded-3">
                <ul class="mb-0 small">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="card table-card">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-1">Buat Reservasi Laboratorium</h6>
                <p class="small text-secondary mb-4">Reservasi yang dibuat laboran langsung berstatus <strong>Disetujui</strong>.</p>

                <form method="POST" action="<?php echo e(route('admin.reservasi.store')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Laboratorium</label>
                        <select name="id_ruangan" class="form-select" required>
                            <option value="">-- Pilih Laboratorium --</option>
                            <?php $__currentLoopData = $labs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($lab->id); ?>" <?php echo e(old('id_ruangan') == $lab->id ? 'selected' : ''); ?>>
                                    <?php echo e($lab->nama_lab); ?> (Kapasitas <?php echo e($lab->kapasitas); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Keperluan</label>
                        <input type="text" name="keperluan" value="<?php echo e(old('keperluan')); ?>" class="form-control" placeholder="Contoh: Praktikum Basis Data" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Tanggal Pemakaian</label>
                        <input type="date" name="tanggal_pakai" value="<?php echo e(old('tanggal_pakai')); ?>" class="form-control" min="<?php echo e(date('Y-m-d')); ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-semibold">Jam Mulai</label>
                            <input type="time" name="jam_mulai" value="<?php echo e(old('jam_mulai')); ?>" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-semibold">Jam Selesai <span class="text-secondary">(maks. 18:10)</span></label>
                            <input type="time" name="jam_selesai" value="<?php echo e(old('jam_selesai')); ?>" class="form-control" max="18:10" required>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-calendar-check me-1"></i> Buat Reservasi
                        </button>
                        <a href="<?php echo e(route('admin.reservasi.index')); ?>" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\RuangLab\resources\views/admin/reservasi/create.blade.php ENDPATH**/ ?>