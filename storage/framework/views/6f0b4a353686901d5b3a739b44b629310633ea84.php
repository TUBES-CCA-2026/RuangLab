<?php
    $layout = auth()->user()->isAdmin() ? 'layouts.admin' : (auth()->user()->isAslab() ? 'layouts.aslab' : 'layouts.app');
?>



<?php $__env->startSection('title', 'Profil Saya'); ?>
<?php $__env->startSection('page-title', 'Profil Saya'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-7">

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3">
                <i class="bi bi-check-circle me-1"></i> <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                         style="width:70px;height:70px;flex-shrink:0;">
                        <i class="bi bi-person-fill fs-2 text-primary"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1"><?php echo e($user->nama); ?></h4>
                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-1">
                            <?php echo e($user->role?->nama_role ?? 'Pengguna'); ?>

                        </span>
                    </div>
                </div>

                <hr>

                <dl class="row small mb-0">
                    <dt class="col-sm-4 text-secondary fw-semibold">Nama Lengkap</dt>
                    <dd class="col-sm-8"><?php echo e($user->nama); ?></dd>

                    <dt class="col-sm-4 text-secondary fw-semibold">Email</dt>
                    <dd class="col-sm-8"><?php echo e($user->email); ?></dd>

                    <dt class="col-sm-4 text-secondary fw-semibold">No. Telepon</dt>
                    <dd class="col-sm-8"><?php echo e($user->no_telp ?: '-'); ?></dd>

                    <dt class="col-sm-4 text-secondary fw-semibold">Role</dt>
                    <dd class="col-sm-8"><?php echo e(ucfirst($user->role?->nama_role ?? '-')); ?></dd>

                    <dt class="col-sm-4 text-secondary fw-semibold">Status Akun</dt>
                    <dd class="col-sm-8">
                        <?php if($user->status): ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle">Aktif</span>
                        <?php else: ?>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Tidak Aktif</span>
                        <?php endif; ?>
                    </dd>
                </dl>

                <div class="mt-4 d-flex gap-2">
                    <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-primary">
                        <i class="bi bi-pencil-fill me-1"></i> Edit Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\RuangLab\resources\views/profile/show.blade.php ENDPATH**/ ?>