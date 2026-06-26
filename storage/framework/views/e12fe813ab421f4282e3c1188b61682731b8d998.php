

<?php $__env->startSection('title', 'Hasil Check-in'); ?>

<?php $__env->startSection('content'); ?>
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card border-0 rounded-xl shadow-sm">
                    <div class="card-body p-5 text-center">

                        <?php if($ok): ?>
                            <div class="mb-3" style="font-size:48px;line-height:1;color:#15b27a;">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <h4 class="fw-bold mb-2">Berhasil</h4>
                        <?php else: ?>
                            <div class="mb-3" style="font-size:48px;line-height:1;color:#e2483d;">
                                <i class="bi bi-x-circle-fill"></i>
                            </div>
                            <h4 class="fw-bold mb-2">Gagal</h4>
                        <?php endif; ?>

                        <p class="text-secondary mb-3"><?php echo e($message); ?></p>

                        <?php if(isset($reservasi)): ?>
                        <div class="bg-light rounded-3 p-3 small text-secondary mb-3">
                            <div class="fw-semibold text-dark"><?php echo e($reservasi->kode_reservasi); ?></div>
                            <?php if(!empty($reservasi->checked_in_at)): ?>
                                <div><i class="bi bi-clock-history me-1"></i>
                                    Check-in <?php echo e(\Carbon\Carbon::parse($reservasi->checked_in_at)->translatedFormat('d M Y H:i')); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <a href="<?php echo e(route('reservasi.index')); ?>" class="btn btn-primary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Reservasi Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\RuangLab\RuangLab\resources\views/reservasi/checkin-result.blade.php ENDPATH**/ ?>