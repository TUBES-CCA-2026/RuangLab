

<?php $__env->startSection('title', 'Dashboard Aslab'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>


<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:54px;height:54px;">
                    <i class="bi bi-calendar-check fs-4 text-primary"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0"><?php echo e($stats['reservasi_saya']); ?></h3>
                    <small class="text-secondary">Total Reservasi Saya</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width:54px;height:54px;">
                    <i class="bi bi-check2-circle fs-4 text-success"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0"><?php echo e($stats['reservasi_aktif']); ?></h3>
                    <small class="text-secondary">Reservasi Aktif</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center" style="width:54px;height:54px;">
                    <i class="bi bi-building fs-4 text-info"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0"><?php echo e($stats['total_lab']); ?></h3>
                    <small class="text-secondary">Lab Tersedia</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center" style="width:54px;height:54px;">
                    <i class="bi bi-door-open fs-4 text-warning"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0"><?php echo e($stats['lab_dipakai']); ?></h3>
                    <small class="text-secondary">Lab Dipakai Hari Ini</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

    
    <div class="col-lg-7">
        <?php echo $__env->make('partials.jadwal-praktikum-hari-ini', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        
        <div class="card table-card mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-semibold mb-0">
                        <i class="bi bi-buildings me-1 text-primary"></i>
                        Status Lab Hari Ini
                        <span class="text-secondary fw-normal small ms-1">— <?php echo e(\Carbon\Carbon::today()->translatedFormat('l, d F Y')); ?></span>
                    </h6>
                </div>

                <?php if($labs->isEmpty()): ?>
                    <p class="text-secondary small mb-0">Belum ada laboratorium aktif.</p>
                <?php else: ?>
                    <div class="row g-3">
                        <?php $__currentLoopData = $labs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $jadwals = $lab->detailReservasi; ?>
                        <div class="col-md-6">
                            <div class="lab-status-card">
                                <div class="lab-header <?php echo e($jadwals->isEmpty() ? 'tersedia' : 'dipakai'); ?>">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <p class="fw-semibold mb-0 small"><?php echo e($lab->nama_lab); ?></p>
                                            <span class="text-secondary" style="font-size:.75rem;">
                                                <i class="bi bi-people me-1"></i><?php echo e($lab->kapasitas); ?> orang
                                            </span>
                                        </div>
                                        <?php if($jadwals->isEmpty()): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size:.7rem;">Kosong</span>
                                        <?php else: ?>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="font-size:.7rem;"><?php echo e($jadwals->count()); ?> jadwal</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="p-2">
                                    <?php if($jadwals->isEmpty()): ?>
                                        <p class="text-secondary small mb-0 px-1 py-2 text-center">
                                            <i class="bi bi-check-circle text-success me-1"></i>Tidak ada pemakaian hari ini
                                        </p>
                                    <?php else: ?>
                                        <div class="d-flex flex-column gap-2 p-1">
                                            <?php $__currentLoopData = $jadwals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="jadwal-item">
                                                <div class="fw-semibold text-dark" style="font-size:.82rem;">
                                                    <i class="bi bi-clock me-1 text-primary"></i>
                                                    <?php echo e(\Illuminate\Support\Str::substr($jd->jam_mulai,0,5)); ?> – <?php echo e(\Illuminate\Support\Str::substr($jd->jam_selesai,0,5)); ?>

                                                    <?php if($jd->reservasi && $jd->reservasi->is_prioritas): ?>
                                                        <span class="badge-prioritas ms-1">Prioritas</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="text-secondary mt-1" style="font-size:.76rem;">
                                                    <i class="bi bi-person me-1"></i><?php echo e($jd->reservasi->user->nama ?? '-'); ?>

                                                    &bull; <?php echo e($jd->reservasi->keperluan ?? '-'); ?>

                                                </div>
                                                <div class="mt-1" style="font-size:.72rem;">
                                                    <span class="badge rounded-pill badge-status-<?php echo e($jd->reservasi->status ?? 'pending'); ?> text-white px-2 py-1">
                                                        <?php echo e(ucwords(str_replace('_', ' ', $jd->reservasi->status ?? '-'))); ?>

                                                    </span>
                                                </div>
                                            </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
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
                    <?php
                        $grouped = $jadwalMendatang->groupBy('tanggal_pakai');
                    ?>

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
                                        <div class="fw-semibold" style="font-size:.82rem;">
                                            <?php echo e($jd->laboratorium->nama_lab ?? '-'); ?>

                                            <?php if($jd->reservasi && $jd->reservasi->is_prioritas): ?>
                                                <span class="badge-prioritas ms-1">Prioritas</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-secondary" style="font-size:.75rem;">
                                            <?php echo e($jd->reservasi->user->nama ?? '-'); ?> &bull; <?php echo e($jd->reservasi->keperluan ?? '-'); ?>

                                        </div>
                                    </div>
                                    <div class="ms-auto">
                                        <span class="badge rounded-pill badge-status-<?php echo e($jd->reservasi->status ?? 'pending'); ?> text-white px-2 py-1" style="font-size:.68rem;">
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
                    <h6 class="fw-semibold mb-0">
                        <i class="bi bi-calendar-check me-1 text-primary"></i>
                        Reservasi Saya
                    </h6>
                    <a href="<?php echo e(route('aslab.reservasi.create')); ?>" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg"></i> Buat
                    </a>
                </div>

                <?php if($reservasiSaya->isEmpty()): ?>
                    <div class="text-center py-4">
                        <i class="bi bi-calendar-x text-secondary" style="font-size:2rem;"></i>
                        <p class="text-secondary small mt-2 mb-3">Belum ada reservasi.</p>
                        <a href="<?php echo e(route('aslab.reservasi.create')); ?>" class="btn btn-sm btn-primary">Buat Reservasi Pertama</a>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-2">
                        <?php $__currentLoopData = $reservasiSaya; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $detail = $r->detail->first(); ?>
                        <div class="p-3 rounded-3 bg-light d-flex flex-column gap-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="fw-semibold small"><?php echo e($r->keperluan); ?></span>
                                <div class="d-flex align-items-center gap-1">
                                    <?php if($r->is_prioritas): ?>
                                        <span class="badge-prioritas"><i class="bi bi-star-fill me-1" style="font-size:.55rem;"></i>Prioritas</span>
                                    <?php endif; ?>
                                    <span class="badge rounded-pill badge-status-<?php echo e($r->status); ?> text-white px-2 py-1" style="font-size:.68rem;">
                                        <?php echo e(ucwords(str_replace('_', ' ', $r->status))); ?>

                                    </span>
                                </div>
                            </div>
                            <div class="text-secondary" style="font-size:.78rem;">
                                <i class="bi bi-building me-1"></i><?php echo e($detail->laboratorium->nama_lab ?? '-'); ?>

                            </div>
                            <?php if($detail): ?>
                            <div class="text-secondary" style="font-size:.76rem;">
                                <i class="bi bi-calendar3 me-1"></i><?php echo e(\Carbon\Carbon::parse($detail->tanggal_pakai)->translatedFormat('d M Y')); ?>

                                &nbsp;<i class="bi bi-clock me-1"></i><?php echo e(\Illuminate\Support\Str::substr($detail->jam_mulai,0,5)); ?> – <?php echo e(\Illuminate\Support\Str::substr($detail->jam_selesai,0,5)); ?>

                            </div>
                            <?php endif; ?>
                            <div class="text-end mt-1">
                                <a href="<?php echo e(route('aslab.reservasi.show', $r->id)); ?>" class="btn btn-xs btn-outline-primary" style="font-size:.75rem;padding:2px 10px;">Detail</a>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="text-center mt-3">
                        <a href="<?php echo e(route('aslab.reservasi.index')); ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.aslab', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\RuangLab\resources\views/aslab/dashboard.blade.php ENDPATH**/ ?>