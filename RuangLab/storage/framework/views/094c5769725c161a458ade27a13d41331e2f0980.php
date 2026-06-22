

<?php $__env->startSection('title', 'Reservasi'); ?>
<?php $__env->startSection('page-title', 'Kelola Reservasi'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <form method="GET" class="d-flex flex-wrap gap-2">
        <input type="text" name="cari" value="<?php echo e(request('cari')); ?>" class="form-control" placeholder="Cari kode reservasi..." style="max-width:220px;">
        <select name="status" class="form-select" style="max-width:180px;" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <?php $__currentLoopData = ['pending','disetujui','ditolak','sedang_dipakai','hangus']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($s); ?>" <?php echo e(request('status') == $s ? 'selected' : ''); ?>><?php echo e(ucwords(str_replace('_',' ',$s))); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
    </form>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Pemohon</th>
                    <th>Laboratorium</th>
                    <th>Tanggal Pakai</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $reservasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="fw-semibold"><?php echo e($r->kode_reservasi); ?></td>
                    <td><?php echo e($r->user->nama ?? '-'); ?></td>
                    <td><?php echo e($r->detail->first()->laboratorium->nama_lab ?? '-'); ?></td>
                    <td><?php echo e(optional($r->detail->first())->tanggal_pakai ? \Carbon\Carbon::parse($r->detail->first()->tanggal_pakai)->format('d M Y') : '-'); ?></td>
                    <td>
                        <span class="badge rounded-pill badge-status-<?php echo e($r->status); ?> text-white px-3 py-2">
                            <?php echo e(ucwords(str_replace('_', ' ', $r->status))); ?>

                        </span>
                    </td>
                    <td class="text-end">
                        <a href="<?php echo e(route('admin.reservasi.show', $r->id)); ?>" class="btn btn-sm btn-outline-primary">Detail</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center text-secondary py-4">Tidak ada data reservasi.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    <?php echo e($reservasis->links()); ?>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\RuangLab-desain-web\RuangLab\resources\views/admin/reservasi/index.blade.php ENDPATH**/ ?>