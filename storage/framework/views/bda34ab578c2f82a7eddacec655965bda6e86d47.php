

<?php $__env->startSection('title', 'History Reservasi'); ?>
<?php $__env->startSection('page-title', 'History Reservasi'); ?>

<?php $__env->startSection('content'); ?>

<div class="card table-card mb-4">
    <div class="card-body p-4">
        <form method="GET" action="<?php echo e(route('aslab.history.index')); ?>" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Cari Nama</label>
                <input type="text" name="cari" value="<?php echo e(request('cari')); ?>" class="form-control" placeholder="Nama peminjam...">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="disetujui"      <?php echo e(request('status')==='disetujui'      ?'selected':''); ?>>Disetujui</option>
                    <option value="ditolak"        <?php echo e(request('status')==='ditolak'        ?'selected':''); ?>>Ditolak</option>
                    <option value="sedang_dipakai" <?php echo e(request('status')==='sedang_dipakai' ?'selected':''); ?>>Sedang Dipakai</option>
                    <option value="hangus"         <?php echo e(request('status')==='hangus'         ?'selected':''); ?>>Hangus</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Laboratorium</label>
                <select name="lab" class="form-select">
                    <option value="">Semua Lab</option>
                    <?php $__currentLoopData = $labs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($lab->id); ?>" <?php echo e(request('lab')===$lab->id ?'selected':''); ?>><?php echo e($lab->nama_lab); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Dari Tanggal</label>
                <input type="date" name="dari" value="<?php echo e(request('dari')); ?>" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Sampai Tanggal</label>
                <input type="date" name="sampai" value="<?php echo e(request('sampai')); ?>" class="form-control">
            </div>
            <div class="col-md-1 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
                <a href="<?php echo e(route('aslab.history.index')); ?>" class="btn btn-outline-secondary w-100"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card table-card">
    <div class="card-body p-0">
        <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
            <h6 class="fw-semibold mb-0">
                <i class="bi bi-clock-history me-2"></i>Riwayat Reservasi
                <span class="badge bg-secondary ms-1"><?php echo e($reservasis->total()); ?></span>
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="small text-secondary">
                        <th class="ps-4">No</th>
                        <th>Peminjam</th>
                        <th>Laboratorium</th>
                        <th>Tanggal Pakai</th>
                        <th>Jam</th>
                        <th>Keperluan</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $reservasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php $detail = $r->detail->first(); ?>
                    <tr>
                        <td class="ps-4 text-secondary small"><?php echo e($reservasis->firstItem() + $loop->index); ?></td>
                        <td>
                            <div class="fw-medium"><?php echo e($r->user->nama ?? '-'); ?></div>
                            <div class="small text-secondary"><?php echo e($r->user->email ?? ''); ?></div>
                        </td>
                        <td><?php echo e($detail->laboratorium->nama_lab ?? '-'); ?></td>
                        <td class="small"><?php echo e($detail ? \Carbon\Carbon::parse($detail->tanggal_pakai)->translatedFormat('d M Y') : '-'); ?></td>
                        <td class="small"><?php echo e($detail ? substr($detail->jam_mulai,0,5).' - '.substr($detail->jam_selesai,0,5) : '-'); ?></td>
                        <td class="small text-secondary" style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?php echo e($r->keperluan); ?></td>
                        <td>
                            <?php if($r->is_prioritas): ?>
                                <span class="badge badge-prioritas">Prioritas</span>
                            <?php else: ?>
                                <span class="badge bg-light text-secondary">Umum</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge rounded-pill badge-status-<?php echo e($r->status); ?> text-white px-2 py-1 small">
                                <?php echo e(ucwords(str_replace('_',' ',$r->status))); ?>

                            </span>
                        </td>
                        <td>
                            <a href="<?php echo e(route('aslab.reservasi.show', $r->id)); ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" class="text-center text-secondary py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i> Belum ada riwayat reservasi.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($reservasis->hasPages()): ?>
        <div class="px-4 py-3 border-top"><?php echo e($reservasis->links()); ?></div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.aslab', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\RuangLab\resources\views/aslab/history/index.blade.php ENDPATH**/ ?>