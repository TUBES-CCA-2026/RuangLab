

<?php $__env->startSection('title', 'Manajemen Pengguna'); ?>
<?php $__env->startSection('page-title', 'Manajemen Pengguna'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h6 class="fw-bold mb-1">Daftar Pengguna</h6>
        <p class="text-secondary small mb-0">Kelola akun pengguna sistem RuangLab</p>
    </div>
    <a href="<?php echo e(route('admin.user.create')); ?>" class="btn btn-primary">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah Pengguna
    </a>
</div>


<form method="GET" class="mb-4">
    <div class="row g-2">
        <div class="col-sm-6 col-md-5">
            <input type="text" name="cari" value="<?php echo e(request('cari')); ?>" class="form-control" placeholder="Cari nama atau email...">
        </div>
        <div class="col-sm-4 col-md-3">
            <select name="role" class="form-select">
                <option value="">Semua Role</option>
                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($role->nama_role); ?>" <?php echo e(request('role') == $role->nama_role ? 'selected' : ''); ?>>
                        <?php echo e(ucfirst($role->nama_role)); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i> Cari</button>
            <a href="<?php echo e(route('admin.user.index')); ?>" class="btn btn-outline-secondary">Reset</a>
        </div>
    </div>
</form>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="fw-semibold"><?php echo e($user->nama); ?></td>
                    <td class="text-secondary small"><?php echo e($user->email); ?></td>
                    <td class="text-secondary small"><?php echo e($user->no_telp ?: '-'); ?></td>
                    <td>
                        <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-1">
                            <?php echo e(ucfirst($user->role?->nama_role ?? '-')); ?>

                        </span>
                    </td>
                    <td>
                        <?php if($user->status): ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle">Aktif</span>
                        <?php else: ?>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <a href="<?php echo e(route('admin.user.edit', $user->id)); ?>" class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <?php if($user->id !== auth()->id()): ?>
                        <form method="POST" action="<?php echo e(route('admin.user.destroy', $user->id)); ?>" class="d-inline"
                              onsubmit="return confirm('Hapus pengguna ini?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center text-secondary py-4">Tidak ada pengguna ditemukan.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4"><?php echo e($users->withQueryString()->links()); ?></div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\RuangLab\resources\views/admin/user/index.blade.php ENDPATH**/ ?>