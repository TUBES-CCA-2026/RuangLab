

<?php $__env->startSection('title', 'Mata Kuliah'); ?>
<?php $__env->startSection('page-title', 'Kelola Mata Kuliah'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <form method="GET" class="d-flex" style="max-width: 320px;">
        <input type="text" name="cari" value="<?php echo e(request('cari')); ?>" class="form-control" placeholder="Cari mata kuliah...">
        <button class="btn btn-outline-secondary ms-2" type="submit"><i class="bi bi-search"></i></button>
    </form>
    <a href="<?php echo e(route('admin.mata-kuliah.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Mata Kuliah
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama Mata Kuliah</th>
                    <th>Dosen</th>
                    <th>SKS</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $matkuls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $matkul): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="fw-semibold"><?php echo e($matkul->nama_matkul); ?></td>
                    <td><?php echo e($matkul->nama_dosen); ?></td>
                    <td><?php echo e($matkul->sks); ?></td>
                    <td class="text-end">
                        <a href="<?php echo e(route('admin.mata-kuliah.edit', $matkul->id)); ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="<?php echo e(route('admin.mata-kuliah.destroy', $matkul->id)); ?>" method="POST" class="d-inline" data-confirm="Yakin ingin menghapus mata kuliah ini?">
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
                    <td colspan="4" class="text-center text-secondary py-4">Belum ada data mata kuliah.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    <?php echo e($matkuls->links()); ?>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\RuangLab\resources\views/admin/mata-kuliah/index.blade.php ENDPATH**/ ?>