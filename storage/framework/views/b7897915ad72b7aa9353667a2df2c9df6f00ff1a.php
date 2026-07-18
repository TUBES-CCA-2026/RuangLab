<?php $__env->startSection('title', 'Tahun Ajaran'); ?>
<?php $__env->startSection('page-title', 'Kelola Tahun Ajaran'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <p class="text-secondary small mb-0">Periode tahun ajaran &amp; semester dipakai untuk memfilter halaman Rekap.</p>
    <a href="<?php echo e(route('admin.tahun-ajaran.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Tahun Ajaran
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Tahun Ajaran</th>
                    <th>Semester</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $tahunAjarans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="fw-semibold"><?php echo e($ta->tahun_ajaran); ?></td>
                    <td><?php echo e(ucfirst($ta->semester)); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($ta->tanggal_mulai)->translatedFormat('d M Y')); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($ta->tanggal_selesai)->translatedFormat('d M Y')); ?></td>
                    <td>
                        <?php if($ta->is_aktif): ?>
                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Aktif</span>
                        <?php else: ?>
                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <a href="<?php echo e(route('admin.tahun-ajaran.edit', $ta->id)); ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="<?php echo e(route('admin.tahun-ajaran.destroy', $ta->id)); ?>" method="POST" class="d-inline" data-confirm="Yakin ingin menghapus tahun ajaran ini?">
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
                    <td colspan="6" class="text-center text-secondary py-4">Belum ada data tahun ajaran.</td>
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