

<?php $__env->startSection('title', 'Beranda'); ?>

<?php $__env->startSection('content'); ?>

<section class="hero-gradient text-white py-5">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge bg-info bg-opacity-25 text-info border border-info rounded-pill px-3 py-2 mb-3">
                    <i class="bi bi-stars"></i> Reservasi Lab Jadi Lebih Mudah
                </span>
                <h1 class="display-5 fw-bold mb-3">Sistem Penjadwalan dan Reservasi Ruangan Laboratorium Berbasis Web</h1>
                <p class="lead text-white-50 mb-4">
                    RuangLab membantu peminjam dan dosen mengajukan, melacak, dan mengelola
                    reservasi laboratorium secara online.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?php echo e(route('laboratorium.index')); ?>" class="btn btn-light btn-lg fw-semibold px-4">
                        <i class="bi bi-search"></i> Jelajahi Laboratorium
                    </a>
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('reservasi.create')); ?>" class="btn btn-outline-light btn-lg px-4">
                            Ajukan Reservasi
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('register')); ?>" class="btn btn-outline-light btn-lg px-4">
                            Daftar Sekarang
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="bg-white bg-opacity-10 border border-white border-opacity-25 rounded-xl p-4 text-center">
                            <i class="bi bi-building fs-1 text-info"></i>
                            <h3 class="fw-bold mt-2 mb-0"><?php echo e($totalLab); ?></h3>
                            <small class="text-white-50">Laboratorium Tersedia</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-white bg-opacity-10 border border-white border-opacity-25 rounded-xl p-4 text-center">
                            <i class="bi bi-lightning-charge fs-1 text-info"></i>
                            <h3 class="fw-bold mt-2 mb-0">24/7</h3>
                            <small class="text-white-50">Pengajuan Online</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="bg-white bg-opacity-10 border border-white border-opacity-25 rounded-xl p-4">
                            <i class="bi bi-shield-check fs-3 text-info"></i>
                            <p class="mb-0 mt-2 small text-white-50">
                                Status reservasi dipantau real-time, lengkap dengan kode check-in untuk validasi di lokasi.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Bagaimana Cara Kerjanya?</h2>
            <p class="text-secondary">Tiga langkah sederhana untuk mendapatkan ruang lab yang kamu butuhkan</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="bg-primary-custom bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:72px;height:72px;">
                        <i class="bi bi-search fs-2 text-primary-custom"></i>
                    </div>
                    <h5 class="fw-semibold">1. Cari Laboratorium</h5>
                    <p class="text-secondary small">Lihat daftar lab, fasilitas, dan kapasitas yang tersedia sesuai kebutuhanmu.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="bg-primary-custom bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:72px;height:72px;">
                        <i class="bi bi-calendar-plus fs-2 text-primary-custom"></i>
                    </div>
                    <h5 class="fw-semibold">2. Ajukan Reservasi</h5>
                    <p class="text-secondary small">Pilih tanggal dan jam pemakaian, lalu kirim pengajuan secara online.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="bg-primary-custom bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:72px;height:72px;">
                        <i class="bi bi-qr-code-scan fs-2 text-primary-custom"></i>
                    </div>
                    <h5 class="fw-semibold">3. Check-in & Gunakan</h5>
                    <p class="text-secondary small">Setelah disetujui, gunakan kode check-in untuk validasi langsung di lab.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if($labUnggulan->isNotEmpty()): ?>
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="fw-bold mb-1">Laboratorium Populer</h2>
                <p class="text-secondary mb-0">Beberapa lab yang sering direservasi</p>
            </div>
            <a href="<?php echo e(route('laboratorium.index')); ?>" class="btn btn-outline-primary d-none d-md-inline-block">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="row g-4">
            <?php $__currentLoopData = $labUnggulan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-4">
                <div class="card card-lab h-100">
                    <?php if($lab->image): ?>
                        <img src="<?php echo e(asset('storage/' . $lab->image)); ?>" class="lab-thumb w-100" alt="<?php echo e($lab->nama_lab); ?>">
                    <?php else: ?>
                        <div class="lab-thumb w-100 d-flex align-items-center justify-content-center">
                            <i class="bi bi-building fs-1 text-primary-custom opacity-50"></i>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title fw-semibold"><?php echo e($lab->nama_lab); ?></h5>
                        <p class="text-secondary small mb-3">
                            <i class="bi bi-people"></i> Kapasitas <?php echo e($lab->kapasitas); ?> orang
                        </p>
                        <a href="<?php echo e(route('laboratorium.show', $lab->id)); ?>" class="btn btn-sm btn-primary w-100">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="py-5">
    <div class="container py-4">
        <div class="rounded-xl hero-gradient text-white p-5 text-center">
            <h3 class="fw-bold mb-2">Siap menggunakan laboratorium kampus?</h3>
            <p class="text-white-50 mb-4">Daftar sekarang dan ajukan reservasi lab pertamamu hari ini.</p>
            <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(route('reservasi.create')); ?>" class="btn btn-light btn-lg fw-semibold px-4">Ajukan Reservasi</a>
            <?php else: ?>
                <a href="<?php echo e(route('register')); ?>" class="btn btn-light btn-lg fw-semibold px-4">Daftar Sekarang</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\RuangLab-desain-web\RuangLab\resources\views/home.blade.php ENDPATH**/ ?>