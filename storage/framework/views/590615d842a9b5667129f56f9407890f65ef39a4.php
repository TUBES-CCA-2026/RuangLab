

<?php $__env->startSection('title', 'Tambah Pengguna'); ?>
<?php $__env->startSection('page-title', 'Tambah Pengguna'); ?>

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
                <h6 class="fw-bold mb-4">Form Tambah Pengguna</h6>

                <form method="POST" action="<?php echo e(route('admin.user.store')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama" value="<?php echo e(old('nama')); ?>" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email</label>
                        <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">No. Telepon</label>
                        <input type="text" name="no_telp" value="<?php echo e(old('no_telp')); ?>" class="form-control" placeholder="Opsional" inputmode="numeric" pattern="[0-9]*" maxlength="13" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Role</label>
                        <select name="id_role" class="form-select" required>
                            <option value="">-- Pilih Role --</option>
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($role->id); ?>" <?php echo e(old('id_role') == $role->id ? 'selected' : ''); ?>>
                                    <?php echo e(ucfirst($role->nama_role)); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="1" <?php echo e(old('status', 1) == 1 ? 'selected' : ''); ?>>Aktif</option>
                            <option value="0" <?php echo e(old('status') == 0 ? 'selected' : ''); ?>>Nonaktif</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="Min. 8 karakter">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-person-plus-fill me-1"></i> Buat Akun
                        </button>
                        <a href="<?php echo e(route('admin.user.index')); ?>" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\RuangLab\RuangLab\resources\views/admin/user/create.blade.php ENDPATH**/ ?>