

<?php $__env->startSection('title', 'Masuk'); ?>

<?php $__env->startSection('content'); ?>
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 rounded-xl shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-flask fs-1 text-primary-custom"></i>
                            <h3 class="fw-bold mt-2">Masuk ke RuangLab</h3>
                            <p class="text-secondary small">Kelola reservasi laboratoriummu dengan mudah</p>
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

                        <form method="POST" action="<?php echo e(route('login')); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Email</label>
                                <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control" placeholder="nama@email.com" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Kata Sandi</label>
                                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label small" for="remember">Ingat saya</label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2">Masuk</button>
                        </form>

                        <p class="text-center small text-secondary mt-4 mb-0">
                            Belum punya akun? <a href="<?php echo e(route('register')); ?>" class="fw-semibold text-decoration-none">Daftar di sini</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\RuangLab\RuangLab\resources\views/auth/login.blade.php ENDPATH**/ ?>