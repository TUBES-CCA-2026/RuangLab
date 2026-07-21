<?php
    $navUser = auth()->user();
    $navUnreadCount = $navUser->unreadNotifications()->count();
    $navLatestNotifs = $navUser->notifications()->latest()->limit(5)->get();
?>
<div class="dropdown rl-notif-dropdown">
    <button class="btn btn-light btn-sm position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-bell fs-5"></i>
        <?php if($navUnreadCount > 0): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger rl-notif-badge" style="font-size:.6rem;"><?php echo e($navUnreadCount > 9 ? '9+' : $navUnreadCount); ?></span>
        <?php endif; ?>
    </button>
    <div class="dropdown-menu dropdown-menu-end rl-notif-panel p-0">
        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
            <span class="fw-semibold small">Notifikasi</span>
            <?php if($navUnreadCount > 0): ?>
                <form method="POST" action="<?php echo e(route('notifications.markAllRead')); ?>" class="m-0">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-link btn-sm p-0 text-decoration-none" style="font-size:.75rem;">Tandai semua dibaca</button>
                </form>
            <?php endif; ?>
        </div>
        <div class="rl-notif-list">
            <?php $__empty_1 = true; $__currentLoopData = $navLatestNotifs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $ndata = $notif->data;
                    $nstatus = $ndata['status'] ?? '';
                    $nicon = $nstatus === 'disetujui' ? 'bi-check-circle-fill text-success'
                        : ($nstatus === 'ditolak' ? 'bi-x-circle-fill text-danger'
                        : ($nstatus === 'hangus' ? 'bi-clock-history text-secondary'
                        : 'bi-info-circle-fill text-primary'));
                    $nlink = '#';
                    if (!empty($ndata['reservasi_id'])) {
                        if ($navUser->isAdmin()) {
                            $nlink = route('admin.reservasi.show', $ndata['reservasi_id']);
                        } elseif ($navUser->isAslab()) {
                            $nlink = route('aslab.reservasi.show', $ndata['reservasi_id']);
                        } else {
                            $nlink = route('reservasi.show', $ndata['reservasi_id']);
                        }
                    }
                ?>
                <a href="<?php echo e($nlink); ?>" class="dropdown-item d-flex gap-2 align-items-start rl-notif-item <?php echo e($notif->read_at ? '' : 'rl-notif-unread'); ?>">
                    <i class="bi <?php echo e($nicon); ?> fs-5 flex-shrink-0 mt-1"></i>
                    <span class="flex-grow-1">
                        <span class="d-block small"><?php echo e($ndata['pesan'] ?? 'Notifikasi baru'); ?></span>
                        <span class="d-block text-secondary" style="font-size:.72rem;"><?php echo e($notif->created_at->diffForHumans()); ?></span>
                    </span>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center text-secondary small py-4">
                    <i class="bi bi-bell-slash fs-4 d-block mb-1"></i>
                    Belum ada notifikasi.
                </div>
            <?php endif; ?>
        </div>
        <div class="text-center border-top py-2">
            <a href="<?php echo e(route('notifications.index')); ?>" class="small text-decoration-none fw-semibold">Lihat semua notifikasi</a>
        </div>
    </div>
</div>
<?php /**PATH D:\RuangLab\RuangLab\resources\views/partials/notification-bell.blade.php ENDPATH**/ ?>