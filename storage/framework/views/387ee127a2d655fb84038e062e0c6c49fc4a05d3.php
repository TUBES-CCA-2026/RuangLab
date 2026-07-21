<?php
    $user = auth()->user();
    $layout = $user->isAdmin() ? 'layouts.admin' : ($user->isAslab() ? 'layouts.aslab' : 'layouts.app');
?>



<?php $__env->startSection('title', 'Notifikasi'); ?>
<?php $__env->startSection('page-title', 'Notifikasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Semua Notifikasi</h6>
            <?php if(auth()->user()->unreadNotifications->count()): ?>
                <form method="POST" action="<?php echo e(route('notifications.markAllRead')); ?>">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-check2-all me-1"></i> Tandai Semua Dibaca
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php $data = $notif->data; ?>
        <div class="card border-0 shadow-sm rounded-3 mb-3 <?php echo e($notif->read_at ? 'opacity-75' : ''); ?>">
            <div class="card-body p-3 d-flex align-items-start gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0
                    <?php echo e(($data['status'] ?? '') === 'disetujui' ? 'bg-success-subtle text-success' :
                       (($data['status'] ?? '') === 'ditolak' ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning')); ?>"
                     style="width:40px;height:40px;">
                    <i class="bi <?php echo e(($data['status'] ?? '') === 'disetujui' ? 'bi-check-circle-fill' :
                                    (($data['status'] ?? '') === 'ditolak' ? 'bi-x-circle-fill' : 'bi-info-circle-fill')); ?>"></i>
                </div>
                <div class="flex-grow-1">
                    <p class="mb-1 fw-semibold small"><?php echo e($data['pesan'] ?? 'Notifikasi baru'); ?></p>
                    <?php if(!empty($data['catatan'])): ?>
                        <p class="mb-1 small text-secondary">Catatan: <?php echo e($data['catatan']); ?></p>
                    <?php endif; ?>
                    <span class="text-secondary" style="font-size:.75rem;">
                        <i class="bi bi-clock me-1"></i><?php echo e($notif->created_at->diffForHumans()); ?>

                        <?php if(!$notif->read_at): ?>
                            <span class="badge bg-primary ms-2" style="font-size:.65rem;">Baru</span>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="d-flex flex-column gap-1 align-items-end flex-shrink-0">
                    <?php if(!empty($data['reservasi_id'])): ?>
                        <?php if(auth()->user()->isAdmin()): ?>
                            <a href="<?php echo e(route('admin.reservasi.show', $data['reservasi_id'])); ?>" class="btn btn-xs btn-outline-primary" style="font-size:.75rem;padding:2px 10px;">Lihat</a>
                        <?php elseif(auth()->user()->isAslab()): ?>
                            <a href="<?php echo e(route('aslab.reservasi.show', $data['reservasi_id'])); ?>" class="btn btn-xs btn-outline-primary" style="font-size:.75rem;padding:2px 10px;">Lihat</a>
                        <?php else: ?>
                            <a href="<?php echo e(route('reservasi.show', $data['reservasi_id'])); ?>" class="btn btn-xs btn-outline-primary" style="font-size:.75rem;padding:2px 10px;">Lihat</a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <form method="POST" action="<?php echo e(route('notifications.destroy', $notif->id)); ?>">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="btn btn-xs btn-outline-danger" style="font-size:.75rem;padding:2px 10px;">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-5">
            <i class="bi bi-bell-slash fs-1 text-secondary"></i>
            <p class="text-secondary mt-2">Belum ada notifikasi.</p>
        </div>
        <?php endif; ?>

        <div class="mt-3"><?php echo e($notifications->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\RuangLab\RuangLab\resources\views/notifications/index.blade.php ENDPATH**/ ?>