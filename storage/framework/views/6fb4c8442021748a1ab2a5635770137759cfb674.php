<?php
    $layout = auth()->user()->isAdmin() ? 'layouts.admin' : (auth()->user()->isAslab() ? 'layouts.aslab' : 'layouts.app');
?>



<?php $__env->startSection('title', 'Edit Profil'); ?>
<?php $__env->startSection('page-title', 'Edit Profil'); ?>

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

        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Edit Profil</h5>

                <form method="POST" action="<?php echo e(route('profile.update')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama" value="<?php echo e(old('nama', $user->nama)); ?>" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email</label>
                        <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">No. Telepon</label>
                        <input type="text" name="no_telp" value="<?php echo e(old('no_telp', $user->no_telp)); ?>" class="form-control" placeholder="Opsional">
                    </div>

                    <hr>
                    <p class="small text-secondary">Kosongkan password jika tidak ingin mengubahnya.</p>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                        </button>
                        <a href="<?php echo e(route('profile.show')); ?>" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\RuangLab\resources\views/profile/edit.blade.php ENDPATH**/ ?>