

<?php $__env->startSection('title', 'Daftar Laboratorium'); ?>

<?php $__env->startSection('content'); ?>
<section class="py-5">
    <div class="container">
        <div class="mb-4">
            <h2 class="fw-bold mb-1">Daftar Laboratorium</h2>
            <p class="text-secondary">Temukan laboratorium yang sesuai dengan kebutuhanmu</p>
        </div>

        <form method="GET" class="mb-4">
            <div class="input-group" style="max-width: 420px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" name="cari" value="<?php echo e(request('cari')); ?>" class="form-control border-start-0" placeholder="Cari nama laboratorium...">
                <button class="btn btn-primary" type="submit">Cari</button>
            </div>
        </form>

        <?php if($labs->isEmpty()): ?>
            <div class="text-center py-5">
                <i class="bi bi-inboxes fs-1 text-secondary"></i>
                <p class="text-secondary mt-2">Belum ada laboratorium yang tersedia.</p>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php $__currentLoopData = $labs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card card-lab h-100">
                        <?php if($lab->image): ?>
                            <img src="<?php echo e(asset('storage/' . $lab->image)); ?>" class="lab-thumb w-100" alt="<?php echo e($lab->nama_lab); ?>">
                        <?php else: ?>
                            <div class="lab-thumb w-100 d-flex align-items-center justify-content-center">
                                <i class="bi bi-building fs-1 text-primary-custom opacity-50"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-semibold"><?php echo e($lab->nama_lab); ?></h5>
                            <p class="text-secondary small mb-3">
                                <i class="bi bi-people"></i> Kapasitas <?php echo e($lab->kapasitas); ?> orang
                            </p>
                            <a href="<?php echo e(route('laboratorium.show', $lab->id)); ?>" class="btn btn-sm btn-primary mt-auto">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="mt-4">
                <?php echo e($labs->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\RuangLab\resources\views/laboratorium/index.blade.php ENDPATH**/ ?>