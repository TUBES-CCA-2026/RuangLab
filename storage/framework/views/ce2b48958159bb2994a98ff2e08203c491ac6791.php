<?php $__env->startSection('title', 'Rekap Tahun Ajaran'); ?>
<?php $__env->startSection('page-title', 'Rekap Tahun Ajaran'); ?>

<?php $__env->startSection('content'); ?>

<div class="card table-card mb-4">
    <div class="card-body p-4">
        <form method="GET" class="d-flex flex-wrap align-items-end gap-2">
            <div>
                <label class="form-label small fw-semibold mb-1">Tahun Ajaran</label>
                <select name="tahun_ajaran" class="form-select" onchange="this.form.submit()">
                    <?php $__empty_1 = true; $__currentLoopData = $tahunAjarans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <option value="<?php echo e($ta->id); ?>" <?php echo e(optional($tahunAjaran)->id == $ta->id ? 'selected' : ''); ?>>
                            <?php echo e($ta->nama); ?><?php echo e($ta->is_aktif ? ' (Aktif)' : ''); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <option value="">Belum ada tahun ajaran</option>
                    <?php endif; ?>
                </select>
            </div>
        </form>
    </div>
</div>

<?php if(!$tahunAjaran): ?>
    <div class="alert alert-warning rounded-3">
        Belum ada data tahun ajaran. Silakan tambahkan tahun ajaran terlebih dahulu di menu
        <a href="<?php echo e(route('admin.tahun-ajaran.index')); ?>">Tahun Ajaran</a>.
    </div>
<?php else: ?>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:54px;height:54px;">
                    <i class="bi bi-calendar-check fs-4 text-primary"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0"><?php echo e($totalReservasi); ?></h3>
                    <small class="text-secondary">Total Reservasi</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width:54px;height:54px;">
                    <i class="bi bi-check-circle fs-4 text-success"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0"><?php echo e($perStatus['disetujui'] ?? 0); ?></h3>
                    <small class="text-secondary">Disetujui</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center" style="width:54px;height:54px;">
                    <i class="bi bi-x-circle fs-4 text-danger"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0"><?php echo e($perStatus['ditolak'] ?? 0); ?></h3>
                    <small class="text-secondary">Ditolak</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:54px;height:54px;">
                    <i class="bi bi-slash-circle fs-4 text-secondary"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0"><?php echo e($totalHangus); ?></h3>
                    <small class="text-secondary">Hangus (No-show)</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="card table-card h-100">
            <div class="card-body p-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-bar-chart-line me-1 text-primary"></i>
                    Reservasi per Bulan
                    <span class="text-secondary fw-normal small ms-1">— <?php echo e($tahunAjaran->nama); ?></span>
                </h6>
                <canvas id="chartPerBulan" height="120"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card table-card h-100">
            <div class="card-body p-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-pie-chart me-1 text-primary"></i>
                    Reservasi per Status
                </h6>
                <div class="d-flex flex-column gap-2">
                    <?php $__currentLoopData = $perStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $jumlah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex justify-content-between align-items-center p-2 rounded-3 bg-light">
                            <span class="badge rounded-pill badge-status-<?php echo e($status); ?> text-white px-2 py-1" style="font-size:.72rem;">
                                <?php echo e(ucwords(str_replace('_', ' ', $status))); ?>

                            </span>
                            <span class="fw-semibold"><?php echo e($jumlah); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card table-card h-100">
            <div class="card-body p-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-building me-1 text-primary"></i>
                    Laboratorium Paling Sering Digunakan
                </h6>
                <?php if($topLabs->isEmpty()): ?>
                    <p class="text-secondary small mb-0">Belum ada data.</p>
                <?php else: ?>
                    <ol class="mb-0 ps-3">
                        <?php $__currentLoopData = $topLabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="mb-2 d-flex justify-content-between">
                                <span><?php echo e($tl->laboratorium->nama_lab ?? '-'); ?></span>
                                <span class="fw-semibold"><?php echo e($tl->total); ?>x</span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ol>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card table-card h-100">
            <div class="card-body p-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-person-check me-1 text-primary"></i>
                    Peminjam Paling Aktif
                </h6>
                <?php if($topPeminjam->isEmpty()): ?>
                    <p class="text-secondary small mb-0">Belum ada data.</p>
                <?php else: ?>
                    <ol class="mb-0 ps-3">
                        <?php $__currentLoopData = $topPeminjam; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="mb-2 d-flex justify-content-between">
                                <span><?php echo e($tp->user->nama ?? '-'); ?></span>
                                <span class="fw-semibold"><?php echo e($tp->total); ?>x</span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ol>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('chartPerBulan'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($perBulan->pluck('label'), 15, 512) ?>,
            datasets: [{
                label: 'Jumlah Reservasi',
                data: <?php echo json_encode($perBulan->pluck('total'), 15, 512) ?>,
                backgroundColor: '#2952e3',
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\RuangLab\resources\views/admin/rekap/index.blade.php ENDPATH**/ ?>