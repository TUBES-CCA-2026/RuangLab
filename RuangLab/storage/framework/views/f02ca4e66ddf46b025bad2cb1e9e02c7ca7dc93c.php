<?php $__env->startSection('title', 'Detail Reservasi'); ?>
<?php $__env->startSection('page-title', 'Detail Reservasi'); ?>

<?php $__env->startSection('content'); ?>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card table-card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h5 class="fw-bold mb-1"><?php echo e($reservasi->kode_reservasi); ?></h5>
                        <p class="text-secondary small mb-0">Diajukan <?php echo e(\Carbon\Carbon::parse($reservasi->tanggal_pengajuan)->format('d M Y')); ?></p>
                    </div>
                    <span class="badge rounded-pill badge-status-<?php echo e($reservasi->status); ?> text-white px-3 py-2">
                        <?php echo e(ucwords(str_replace('_', ' ', $reservasi->status))); ?>

                    </span>
                </div>

                <hr>

                <h6 class="fw-semibold mb-2">Pemohon</h6>
                <ul class="list-unstyled small text-secondary mb-4">
                    <li><i class="bi bi-person me-1"></i> <?php echo e($reservasi->user->nama ?? '-'); ?></li>
                    <li><i class="bi bi-envelope me-1"></i> <?php echo e($reservasi->user->email ?? '-'); ?></li>
                    <li><i class="bi bi-telephone me-1"></i> <?php echo e($reservasi->user->no_telp ?? '-'); ?></li>
                </ul>

                <h6 class="fw-semibold mb-2">Detail Pemakaian</h6>
                <?php $__currentLoopData = $reservasi->detail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="mb-3 p-3 bg-light rounded-3">
                    <p class="fw-semibold mb-1"><i class="bi bi-building"></i> <?php echo e($d->laboratorium->nama_lab ?? '-'); ?></p>
                    <p class="small text-secondary mb-0">
                        <i class="bi bi-calendar3"></i> <?php echo e(\Carbon\Carbon::parse($d->tanggal_pakai)->translatedFormat('d F Y')); ?>

                        &nbsp;|&nbsp;
                        <i class="bi bi-clock"></i> <?php echo e(\Illuminate\Support\Str::substr($d->jam_mulai,0,5)); ?> - <?php echo e(\Illuminate\Support\Str::substr($d->jam_selesai,0,5)); ?>

                    </p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <p class="mb-0"><span class="fw-semibold">Keperluan:</span> <?php echo e($reservasi->keperluan); ?></p>

                <?php if($reservasi->status === 'disetujui'): ?>
                <div class="alert alert-success rounded-3 mt-3 mb-0">
                    <i class="bi bi-key"></i> Kode check-in: <span class="fw-bold"><?php echo e($reservasi->kode_checkin); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card table-card">
            <div class="card-body p-4">
                <h6 class="fw-semibold mb-3">Update Status</h6>
                <form method="POST" action="<?php echo e(route('admin.reservasi.updateStatus', $reservasi->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Status Baru</label>
                        <select name="status" class="form-select" required>
                            <option value="disetujui" <?php echo e($reservasi->status == 'disetujui' ? 'selected' : ''); ?>>Setujui</option>
                            <option value="ditolak" <?php echo e($reservasi->status == 'ditolak' ? 'selected' : ''); ?>>Tolak</option>
                            <option value="sedang_dipakai" <?php echo e($reservasi->status == 'sedang_dipakai' ? 'selected' : ''); ?>>Sedang Dipakai</option>
                            <option value="hangus" <?php echo e($reservasi->status == 'hangus' ? 'selected' : ''); ?>>Hangus</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Catatan Admin (opsional)</label>
                        <textarea name="catatan_admin" rows="3" class="form-control" placeholder="Catatan untuk pemohon..."><?php echo e($reservasi->catatan_admin); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\RuangLab-desain-web\RuangLab\resources\views/admin/reservasi/show.blade.php ENDPATH**/ ?>