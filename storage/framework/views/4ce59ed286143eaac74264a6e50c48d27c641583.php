

<?php $__env->startSection('title', 'Daftar Akun'); ?>

<?php $__env->startSection('content'); ?>
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card border-0 rounded-xl shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-flask fs-1 text-primary-custom"></i>
                            <h3 class="fw-bold mt-2">Buat Akun Baru</h3>
                            <p class="text-secondary small">Daftar untuk mulai mengajukan reservasi laboratorium</p>
                        </div>

                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger rounded-3">
                                <ul class="mb-0 small">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo e(route('register')); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Nama Lengkap</label>
                                <input type="text" name="nama" value="<?php echo e(old('nama')); ?>" class="form-control" placeholder="Nama lengkap" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Email</label>
                                <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control" placeholder="nama@email.com" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">No. Telepon (opsional)</label>
                                <input type="text" name="no_telp" value="<?php echo e(old('no_telp')); ?>" class="form-control" placeholder="08xxxxxxxxxx">
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label small fw-semibold">Kata Sandi</label>
                                    <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label small fw-semibold">Konfirmasi Sandi</label>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi sandi" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2 mt-2">Daftar</button>
                        </form>

                        <p class="text-center small text-secondary mt-4 mb-0">
                            Sudah punya akun? <a href="<?php echo e(route('login')); ?>" class="fw-semibold text-decoration-none">Masuk di sini</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\RuangLab\resources\views/auth/register.blade.php ENDPATH**/ ?>