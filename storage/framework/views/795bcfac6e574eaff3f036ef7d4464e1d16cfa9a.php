

<?php $__env->startSection('title', 'Ajukan Reservasi'); ?>
<?php $__env->startSection('page-title', 'Ajukan Reservasi Prioritas'); ?>

<?php $__env->startSection('content'); ?>

<div class="row justify-content-center">
    <div class="col-lg-7">

        <div class="alert d-flex align-items-start gap-3 rounded-3 mb-4"
             style="background:#fefce8;border:1px solid #fde68a;color:#78350f;">
            <i class="bi bi-star-fill mt-1" style="font-size:1.1rem;color:#f59e0b;flex-shrink:0;"></i>
            <div>
                <p class="fw-semibold mb-1">Reservasi Prioritas Asisten Lab</p>
                <p class="mb-0 small">Sebagai asisten laboratorium, reservasimu akan <strong>langsung disetujui</strong> secara otomatis tanpa menunggu persetujuan admin.</p>
            </div>
        </div>

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
                <form method="POST" action="<?php echo e(route('aslab.reservasi.store')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Pilih Laboratorium</label>
                        <select name="id_ruangan" class="form-select" required>
                            <option value="">-- Pilih Laboratorium --</option>
                            <?php $__currentLoopData = $labs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($lab->id); ?>" <?php echo e(old('id_ruangan', $labTerpilih) == $lab->id ? 'selected' : ''); ?>>
                                    <?php echo e($lab->nama_lab); ?> (Kapasitas <?php echo e($lab->kapasitas); ?> orang)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Keperluan</label>
                        <input type="text" name="keperluan" value="<?php echo e(old('keperluan')); ?>" class="form-control"
                               placeholder="Contoh: Asistensi Praktikum Basis Data" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Tanggal Pemakaian</label>
                        <input type="date" name="tanggal_pakai" value="<?php echo e(old('tanggal_pakai')); ?>"
                               class="form-control" min="<?php echo e(date('Y-m-d')); ?>" required>
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

                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="bi bi-star-fill me-1"></i> Buat Reservasi Prioritas
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.aslab', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\RuangLab\resources\views/aslab/reservasi/create.blade.php ENDPATH**/ ?>