<?php $__env->startSection('title', 'Edit Pengguna'); ?>
<?php $__env->startSection('page-title', 'Edit Pengguna'); ?>

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
                <h6 class="fw-bold mb-4">Edit Pengguna: <?php echo e($user->nama); ?></h6>

                <form method="POST" action="<?php echo e(route('admin.user.update', $user->id)); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

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
                        <input type="text" name="no_telp" value="<?php echo e(old('no_telp', $user->no_telp)); ?>" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Role</label>
                        <select name="id_role" class="form-select" required>
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($role->id); ?>" <?php echo e(old('id_role', $user->id_role) == $role->id ? 'selected' : ''); ?>>
                                    <?php echo e(ucfirst($role->nama_role)); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="1" <?php echo e(old('status', $user->status) == 1 ? 'selected' : ''); ?>>Aktif</option>
                            <option value="0" <?php echo e(old('status', $user->status) == 0 ? 'selected' : ''); ?>>Nonaktif</option>
                        </select>
                    </div>

                    <hr>
                    <p class="small text-secondary">Kosongkan password jika tidak ingin mengubahnya.</p>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Password Baru</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan
                        </button>
                        <a href="<?php echo e(route('admin.user.index')); ?>" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\RuangLab\RuangLab\resources\views/admin/user/edit.blade.php ENDPATH**/ ?>