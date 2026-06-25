

<?php $__env->startSection('title', 'Reservasi Saya'); ?>

<?php $__env->startSection('content'); ?>
<section class="py-5">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1">Reservasi Saya</h2>
                <p class="text-secondary mb-0">Pantau status pengajuan reservasi laboratoriummu</p>
            </div>
            <a href="<?php echo e(route('reservasi.create')); ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Ajukan Reservasi Baru
            </a>
        </div>

        <?php if($reservasis->isEmpty()): ?>
            <div class="text-center py-5 bg-white rounded-xl">
                <i class="bi bi-calendar-x fs-1 text-secondary"></i>
                <p class="text-secondary mt-2 mb-3">Kamu belum memiliki riwayat reservasi.</p>
                <a href="<?php echo e(route('reservasi.create')); ?>" class="btn btn-primary">Ajukan Reservasi Pertamamu</a>
            </div>
        <?php else: ?>
            <div class="card border-0 rounded-xl shadow-sm">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Laboratorium</th>
                                <th>Tanggal Pakai</th>
                                <th>Jam</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $reservasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $d = $r->detail->first(); ?>
                            <tr>
                                <td class="fw-semibold"><?php echo e($d?->laboratorium->nama_lab ?? '-'); ?></td>
                                <td><?php echo e($d?->tanggal_pakai ? \Carbon\Carbon::parse($d->tanggal_pakai)->translatedFormat('d M Y') : '-'); ?></td>
                                <td class="text-secondary small"><?php echo e($d ? \Illuminate\Support\Str::substr($d->jam_mulai,0,5).' - '.\Illuminate\Support\Str::substr($d->jam_selesai,0,5) : '-'); ?></td>
                                <td>
                                    <span class="badge rounded-pill badge-status-<?php echo e($r->status); ?> text-white px-3 py-2">
                                        <?php echo e(ucwords(str_replace('_', ' ', $r->status))); ?>

                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="<?php echo e(route('reservasi.show', $r->id)); ?>" class="btn btn-sm btn-outline-primary me-1">Detail</a>
                                    <?php if($r->status === 'pending'): ?>
                                    <a href="<?php echo e(route('reservasi.edit', $r->id)); ?>" class="btn btn-sm btn-outline-secondary me-1">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="<?php echo e(route('reservasi.destroy', $r->id)); ?>" class="d-inline"
                                          onsubmit="return confirm('Batalkan reservasi ini?')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                <?php echo e($reservasis->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\RuangLab\resources\views/reservasi/index.blade.php ENDPATH**/ ?>