

<?php $__env->startSection('title', 'Edit Reservasi'); ?>
<?php $__env->startSection('page-title', 'Edit Reservasi'); ?>

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
                <h6 class="fw-bold mb-1">Edit Reservasi</h6>
                <p class="small text-secondary mb-4">Pemohon: <strong><?php echo e($reservasi->user->nama ?? '-'); ?></strong></p>

                <?php $detail = $reservasi->detail->first(); ?>

                <form method="POST" action="<?php echo e(route('admin.reservasi.update', $reservasi->id)); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Laboratorium</label>
                        <select name="id_ruangan" class="form-select" required>
                            <?php $__currentLoopData = $labs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($lab->id); ?>" <?php echo e(old('id_ruangan', $detail?->id_ruangan) == $lab->id ? 'selected' : ''); ?>>
                                    <?php echo e($lab->nama_lab); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Keperluan</label>
                        <input type="text" name="keperluan" value="<?php echo e(old('keperluan', $reservasi->keperluan)); ?>" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Mata Kuliah <span class="text-secondary">(opsional)</span></label>
                        <select name="id_matkul" class="form-select">
                            <option value="">-- Tanpa Mata Kuliah --</option>
                            <?php $__currentLoopData = $matkuls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $matkul): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($matkul->id); ?>" <?php echo e(old('id_matkul', $detail?->id_matkul) == $matkul->id ? 'selected' : ''); ?>>
                                    <?php echo e($matkul->nama_matkul); ?> (<?php echo e($matkul->nama_dosen); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Tanggal Pemakaian</label>
                        <input type="date" name="tanggal_pakai" value="<?php echo e(old('tanggal_pakai', $detail?->tanggal_pakai)); ?>" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-semibold">Jam Mulai</label>
                            <input type="time" name="jam_mulai" value="<?php echo e(old('jam_mulai', Str::substr($detail?->jam_mulai, 0, 5))); ?>" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-semibold">Jam Selesai <span class="text-secondary">(maks. 18:10)</span></label>
                            <input type="time" name="jam_selesai" value="<?php echo e(old('jam_selesai', Str::substr($detail?->jam_selesai, 0, 5))); ?>" class="form-control" max="18:10" required>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Simpan</button>
                        <a href="<?php echo e(route('admin.reservasi.show', $reservasi->id)); ?>" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\RuangLab\resources\views/admin/reservasi/edit.blade.php ENDPATH**/ ?>