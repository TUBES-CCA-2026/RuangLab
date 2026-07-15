<?php $__env->startSection('title', 'Tahun Ajaran'); ?>
<?php $__env->startSection('page-title', 'Kelola Tahun Ajaran'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <form method="GET" class="d-flex" style="max-width: 320px;">
        <input type="text" name="cari" value="<?php echo e(request('cari')); ?>" class="form-control" placeholder="Cari tahun ajaran...">
        <button class="btn btn-outline-secondary ms-2" type="submit"><i class="bi bi-search"></i></button>
    </form>
    <a href="<?php echo e(route('admin.tahun-ajaran.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Tahun Ajaran
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Semester</th>
                    <th>Periode</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $tahunAjarans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="fw-semibold"><?php echo e($ta->nama); ?></td>
                    <td><?php echo e(ucfirst($ta->semester)); ?></td>
                    <td><?php echo e($ta->tanggal_mulai->format('d/m/Y')); ?> &ndash; <?php echo e($ta->tanggal_selesai->format('d/m/Y')); ?></td>
                    <td>
                        <?php if($ta->is_aktif): ?>
                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Aktif</span>
                        <?php else: ?>
                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <?php if (! ($ta->is_aktif)): ?>
                        <form action="<?php echo e(route('admin.tahun-ajaran.aktifkan', $ta->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Aktifkan tahun ajaran ini? Tahun ajaran lain akan dinonaktifkan.');">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-check-circle"></i> Aktifkan
                            </button>
                        </form>
                        <?php endif; ?>
                        <a href="<?php echo e(route('admin.tahun-ajaran.edit', $ta->id)); ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="<?php echo e(route('admin.tahun-ajaran.destroy', $ta->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus tahun ajaran ini?');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="text-center text-secondary py-4">Belum ada data tahun ajaran.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    <?php echo e($tahunAjarans->links()); ?>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\RuangLab\resources\views/admin/tahun-ajaran/index.blade.php ENDPATH**/ ?>