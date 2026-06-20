<?php $__env->startSection('title', $lab->nama_lab); ?>

<?php $__env->startSection('content'); ?>
<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Beranda</a></li>
                <li class="breadcrumb-item"><a href="<?php echo e(route('laboratorium.index')); ?>">Laboratorium</a></li>
                <li class="breadcrumb-item active"><?php echo e($lab->nama_lab); ?></li>
            </ol>
        </nav>

        <div class="row g-4">
            <div class="col-lg-7">
                <?php if($lab->image): ?>
                    <img src="<?php echo e(asset('storage/' . $lab->image)); ?>" class="w-100 rounded-xl mb-4" style="height: 320px; object-fit: cover;" alt="<?php echo e($lab->nama_lab); ?>">
                <?php else: ?>
                    <div class="w-100 rounded-xl mb-4 d-flex align-items-center justify-content-center bg-light" style="height: 320px;">
                        <i class="bi bi-building" style="font-size: 4rem; color: var(--rl-primary); opacity: .4;"></i>
                    </div>
                <?php endif; ?>

                <h2 class="fw-bold mb-2"><?php echo e($lab->nama_lab); ?></h2>
                <p class="text-secondary mb-4">
                    <i class="bi bi-person-badge"></i> Penanggung jawab: <?php echo e($lab->penanggungJawab->nama ?? '-'); ?>

                </p>

                <div class="card border-0 rounded-xl shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3"><i class="bi bi-tools"></i> Fasilitas</h6>
                        <p class="mb-0 text-secondary">
                            <?php echo e($lab->fasilitas ?: 'Belum ada informasi fasilitas untuk laboratorium ini.'); ?>

                        </p>
                    </div>
                </div>

                <?php if($lab->jadwalKuliah->isNotEmpty()): ?>
                <div class="card border-0 rounded-xl shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3"><i class="bi bi-calendar-week"></i> Jadwal Kuliah Rutin</h6>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead>
                                    <tr class="text-secondary small">
                                        <th>Hari</th>
                                        <th>Jam</th>
                                        <th>Mata Kuliah</th>
                                        <th>Dosen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $lab->jadwalKuliah; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($jadwal->hari->nama_hari ?? '-'); ?></td>
                                        <td><?php echo e(\Illuminate\Support\Str::substr($jadwal->jam_mulai, 0, 5)); ?> - <?php echo e(\Illuminate\Support\Str::substr($jadwal->jam_selesai, 0, 5)); ?></td>
                                        <td><?php echo e($jadwal->mataKuliah->nama_matkul ?? '-'); ?></td>
                                        <td><?php echo e($jadwal->mataKuliah->nama_dosen ?? '-'); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 rounded-xl shadow-sm sticky-top" style="top: 90px;">
                    <div class="card-body p-4">
                        <h5 class="fw-semibold mb-3">Informasi Singkat</h5>
                        <ul class="list-unstyled mb-4">
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-secondary"><i class="bi bi-people"></i> Kapasitas</span>
                                <span class="fw-semibold"><?php echo e($lab->kapasitas); ?> orang</span>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-secondary"><i class="bi bi-toggle-on"></i> Status</span>
                                <span class="fw-semibold text-success">Tersedia</span>
                            </li>
                            <li class="d-flex justify-content-between py-2">
                                <span class="text-secondary"><i class="bi bi-person-badge"></i> Penanggung Jawab</span>
                                <span class="fw-semibold"><?php echo e($lab->penanggungJawab->nama ?? '-'); ?></span>
                            </li>
                        </ul>

                        <?php if(auth()->guard()->check()): ?>
                            <a href="<?php echo e(route('reservasi.create', ['lab' => $lab->id])); ?>" class="btn btn-primary w-100">
                                <i class="bi bi-calendar-plus"></i> Ajukan Reservasi Lab Ini
                            </a>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="btn btn-primary w-100">
                                <i class="bi bi-box-arrow-in-right"></i> Masuk untuk Reservasi
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\RuangLab-desain-web\RuangLab\resources\views/laboratorium/show.blade.php ENDPATH**/ ?>