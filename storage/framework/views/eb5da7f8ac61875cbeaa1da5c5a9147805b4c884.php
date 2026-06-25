

<?php $__env->startSection('title', 'Reservasi'); ?>
<?php $__env->startSection('page-title', 'Kelola Reservasi'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <form method="GET" class="d-flex flex-wrap gap-2">
        <input type="text" name="cari" value="<?php echo e(request('cari')); ?>" class="form-control" placeholder="Cari nama pemohon..." style="max-width:220px;">
        <select name="status" class="form-select" style="max-width:180px;" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <?php $__currentLoopData = ['pending','disetujui','ditolak','sedang_dipakai','hangus']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($s); ?>" <?php echo e(request('status') == $s ? 'selected' : ''); ?>><?php echo e(ucwords(str_replace('_',' ',$s))); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
    </form>
    <a href="<?php echo e(route('admin.reservasi.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Buat Reservasi
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Pemohon</th>
                    <th>Laboratorium</th>
                    <th>Tanggal Pakai</th>
                    <th>Jam</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $reservasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php $d = $r->detail->first(); ?>
                <tr>
                    <td>
                        <span class="fw-semibold"><?php echo e($r->user->nama ?? '-'); ?></span>
                        <?php if($r->is_prioritas): ?>
                            <br><span class="badge rounded-pill text-white px-2 py-1 mt-1"
                                      style="font-size:.65rem;background:linear-gradient(135deg,#f59e0b,#ef4444);">
                                <i class="bi bi-star-fill me-1" style="font-size:.5rem;"></i>Prioritas
                            </span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($d?->laboratorium->nama_lab ?? '-'); ?></td>
                    <td><?php echo e($d?->tanggal_pakai ? \Carbon\Carbon::parse($d->tanggal_pakai)->format('d M Y') : '-'); ?></td>
                    <td class="text-secondary small"><?php echo e($d ? \Illuminate\Support\Str::substr($d->jam_mulai,0,5).' – '.\Illuminate\Support\Str::substr($d->jam_selesai,0,5) : '-'); ?></td>
                    <td>
                        <span class="badge rounded-pill badge-status-<?php echo e($r->status); ?> text-white px-3 py-2">
                            <?php echo e(ucwords(str_replace('_', ' ', $r->status))); ?>

                        </span>
                    </td>
                    <td class="text-end">
                        <a href="<?php echo e(route('admin.reservasi.show', $r->id)); ?>" class="btn btn-sm btn-outline-primary me-1">Detail</a>
                        <form method="POST" action="<?php echo e(route('admin.reservasi.destroy', $r->id)); ?>" class="d-inline"
                              onsubmit="return confirm('Hapus reservasi ini?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\RuangLab\RuangLab\resources\views/admin/reservasi/index.blade.php ENDPATH**/ ?>