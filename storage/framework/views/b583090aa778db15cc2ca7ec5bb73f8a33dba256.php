<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:54px;height:54px;">
                    <i class="bi bi-building fs-4 text-primary"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0"><?php echo e($stats['total_lab']); ?></h3>
                    <small class="text-secondary">Total Laboratorium</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width:54px;height:54px;">
                    <i class="bi bi-toggle-on fs-4 text-success"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0"><?php echo e($stats['lab_aktif']); ?></h3>
                    <small class="text-secondary">Lab Aktif</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center" style="width:54px;height:54px;">
                    <i class="bi bi-hourglass-split fs-4 text-warning"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0"><?php echo e($stats['reservasi_pending']); ?></h3>
                    <small class="text-secondary">Menunggu Persetujuan</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center" style="width:54px;height:54px;">
                    <i class="bi bi-people fs-4 text-info"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0"><?php echo e($stats['total_user']); ?></h3>
                    <small class="text-secondary">Total Pengguna</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-lg-7">

        
        <div class="card table-card mb-4">
            <div class="card-body p-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-buildings me-1 text-primary"></i>
                    Status Lab Hari Ini
                    <span class="text-secondary fw-normal small ms-1">— <?php echo e(\Carbon\Carbon::today()->translatedFormat('l, d F Y')); ?></span>
                </h6>

                <?php if($labs->isEmpty()): ?>
                    <p class="text-secondary small">Belum ada laboratorium aktif.</p>
                <?php else: ?>
                    <div class="row g-3">
                        <?php $__currentLoopData = $labs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $jadwals = $lab->detailReservasi; ?>
                        <div class="col-md-6">
                            <div class="border rounded-3 overflow-hidden">
                                <div class="p-2 <?php echo e($jadwals->isEmpty() ? 'bg-success-subtle border-start border-success border-3' : 'bg-primary-subtle border-start border-primary border-3'); ?>">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <p class="fw-semibold mb-0 small"><?php echo e($lab->nama_lab); ?></p>
                                        <?php if($jadwals->isEmpty()): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size:.7rem;">Kosong</span>
                                        <?php else: ?>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size:.7rem;"><?php echo e($jadwals->count()); ?> jadwal</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="p-2">
                                    <?php if($jadwals->isEmpty()): ?>
                                        <p class="text-secondary small mb-0 text-center py-1">Tidak ada pemakaian</p>
                                    <?php else: ?>
                                        <?php $__currentLoopData = $jadwals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="border-start border-primary border-2 ps-2 mb-1 rounded" style="font-size:.78rem;">
                                            <div class="fw-semibold"><i class="bi bi-clock me-1 text-primary"></i>
                                                <?php echo e(\Illuminate\Support\Str::substr($jd->jam_mulai,0,5)); ?> – <?php echo e(\Illuminate\Support\Str::substr($jd->jam_selesai,0,5)); ?>

                                            </div>
                                            <div class="text-secondary"><?php echo e($jd->reservasi->user->nama ?? '-'); ?> · <?php echo e($jd->reservasi->keperluan ?? '-'); ?></div>
                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card table-card">
            <div class="card-body p-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-calendar-week me-1 text-primary"></i>
                    Jadwal 7 Hari Ke Depan
                </h6>
                <?php if($jadwalMendatang->isEmpty()): ?>
                    <p class="text-secondary small mb-0">Tidak ada jadwal reservasi dalam 7 hari ke depan.</p>
                <?php else: ?>
                    <?php $grouped = $jadwalMendatang->groupBy('tanggal_pakai'); ?>
                    <div class="d-flex flex-column gap-3">
                        <?php $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tanggal => $jadwals): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold"
                                     style="width:32px;height:32px;font-size:.75rem;flex-shrink:0;">
                                    <?php echo e(\Carbon\Carbon::parse($tanggal)->format('d')); ?>

                                </div>
                                <span class="fw-semibold small">
                                    <?php echo e(\Carbon\Carbon::parse($tanggal)->translatedFormat('l, d M Y')); ?>

                                    <?php if(\Carbon\Carbon::parse($tanggal)->isToday()): ?>
                                        <span class="badge bg-success-subtle text-success ms-1" style="font-size:.68rem;">Hari ini</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="d-flex flex-column gap-2 ms-4">
                                <?php $__currentLoopData = $jadwals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="d-flex align-items-start gap-2 p-2 rounded-3 bg-light">
                                    <div class="text-primary fw-semibold text-nowrap" style="font-size:.8rem;min-width:95px;">
                                        <?php echo e(\Illuminate\Support\Str::substr($jd->jam_mulai,0,5)); ?> – <?php echo e(\Illuminate\Support\Str::substr($jd->jam_selesai,0,5)); ?>

                                    </div>
                                    <div>
                                        <div class="fw-semibold" style="font-size:.82rem;"><?php echo e($jd->laboratorium->nama_lab ?? '-'); ?></div>
                                        <div class="text-secondary" style="font-size:.75rem;"><?php echo e($jd->reservasi->user->nama ?? '-'); ?> · <?php echo e($jd->reservasi->keperluan ?? '-'); ?></div>
                                    </div>
                                    <div class="ms-auto">
                                        <span class="badge rounded-pill badge-status-<?php echo e($jd->reservasi->status ?? 'pending'); ?> text-white px-2" style="font-size:.68rem;">
                                            <?php echo e(ucwords(str_replace('_', ' ', $jd->reservasi->status ?? '-'))); ?>

                                        </span>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="col-lg-5">
        <div class="card table-card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-semibold mb-0">Reservasi Terbaru</h6>
                    <a href="<?php echo e(route('admin.reservasi.index')); ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>

                <?php if($reservasiTerbaru->isEmpty()): ?>
                    <p class="text-secondary small mb-0">Belum ada pengajuan reservasi.</p>
                <?php else: ?>
                    <div class="d-flex flex-column gap-2">
                        <?php $__currentLoopData = $reservasiTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $d = $r->detail->first(); ?>
                        <div class="p-3 bg-light rounded-3">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <span class="fw-semibold small"><?php echo e($r->user->nama ?? '-'); ?></span>
                                <span class="badge rounded-pill badge-status-<?php echo e($r->status); ?> text-white px-2 py-1" style="font-size:.68rem;">
                                    <?php echo e(ucwords(str_replace('_', ' ', $r->status))); ?>

                                </span>
                            </div>
                            <div class="text-secondary" style="font-size:.76rem;">
                                <i class="bi bi-building me-1"></i><?php echo e($d?->laboratorium->nama_lab ?? '-'); ?>

                                <?php if($d): ?>
                                · <i class="bi bi-calendar3 me-1"></i><?php echo e(\Carbon\Carbon::parse($d->tanggal_pakai)->format('d M Y')); ?>

                                <?php endif; ?>
                            </div>
                            <div class="text-end mt-1">
                                <a href="<?php echo e(route('admin.reservasi.show', $r->id)); ?>" class="btn btn-xs btn-outline-primary" style="font-size:.72rem;padding:2px 8px;">Detail</a>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\RuangLab\RuangLab\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>