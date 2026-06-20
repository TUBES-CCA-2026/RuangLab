<?php $__env->startSection('title', 'Detail Reservasi'); ?>

<?php $__env->startSection('content'); ?>
<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="<?php echo e(route('reservasi.index')); ?>">Reservasi Saya</a></li>
                <li class="breadcrumb-item active"><?php echo e($reservasi->kode_reservasi); ?></li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card border-0 rounded-xl shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h4 class="fw-bold mb-1"><?php echo e($reservasi->kode_reservasi); ?></h4>
                                <p class="text-secondary small mb-0">Diajukan <?php echo e(\Carbon\Carbon::parse($reservasi->tanggal_pengajuan)->translatedFormat('d M Y')); ?></p>
                            </div>
                            <span class="badge rounded-pill badge-status-<?php echo e($reservasi->status); ?> text-white px-3 py-2">
                                <?php echo e(ucwords(str_replace('_', ' ', $reservasi->status))); ?>

                            </span>
                        </div>

                        <hr>

                        <?php $__currentLoopData = $reservasi->detail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-2"><i class="bi bi-building"></i> <?php echo e($d->laboratorium->nama_lab ?? '-'); ?></h6>
                            <ul class="list-unstyled small text-secondary mb-0">
                                <li class="mb-1"><i class="bi bi-calendar3 me-1"></i> <?php echo e(\Carbon\Carbon::parse($d->tanggal_pakai)->translatedFormat('d F Y')); ?></li>
                                <li><i class="bi bi-clock me-1"></i> <?php echo e(\Illuminate\Support\Str::substr($d->jam_mulai,0,5)); ?> - <?php echo e(\Illuminate\Support\Str::substr($d->jam_selesai,0,5)); ?></li>
                            </ul>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <hr>

                        <p class="mb-1"><span class="fw-semibold">Keperluan:</span> <?php echo e($reservasi->keperluan); ?></p>

                        <?php if($reservasi->status === 'disetujui'): ?>
                        <div class="alert alert-success rounded-3 mt-3">
                            <i class="bi bi-check-circle"></i> Reservasi disetujui! Gunakan kode check-in berikut saat tiba di lokasi:
                            <div class="fw-bold fs-5 mt-2"><?php echo e($reservasi->kode_checkin); ?></div>
                        </div>
                        <?php elseif($reservasi->status === 'ditolak'): ?>
                        <div class="alert alert-danger rounded-3 mt-3">
                            <i class="bi bi-x-circle"></i> Reservasi ditolak.
                        </div>
                        <?php elseif($reservasi->status === 'pending'): ?>
                        <div class="alert alert-warning rounded-3 mt-3">
                            <i class="bi bi-hourglass-split"></i> Menunggu persetujuan admin.
                        </div>
                        <?php endif; ?>

                        <?php if($reservasi->catatan_admin): ?>
                        <div class="mt-3">
                            <span class="fw-semibold small">Catatan Admin:</span>
                            <p class="text-secondary small mb-0"><?php echo e($reservasi->catatan_admin); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\RuangLab-desain-web\RuangLab\resources\views/reservasi/show.blade.php ENDPATH**/ ?>