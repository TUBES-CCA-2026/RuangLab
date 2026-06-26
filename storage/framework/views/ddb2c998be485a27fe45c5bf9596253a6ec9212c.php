

<?php $__env->startSection('title', 'Edit Reservasi'); ?>

<?php $__env->startSection('content'); ?>
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="mb-4">
                    <h2 class="fw-bold mb-1">Edit Reservasi</h2>
                    <p class="text-secondary">Perbarui detail reservasi laboratoriummu</p>
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

                <?php $detail = $reservasi->detail->first(); ?>

                <div class="card border-0 rounded-xl shadow-sm">
                    <div class="card-body p-4">
                        <form method="POST" action="<?php echo e(route('reservasi.update', $reservasi->id)); ?>">
                            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Pilih Laboratorium</label>
                                <select name="id_ruangan" class="form-select" required>
                                    <option value="">-- Pilih Laboratorium --</option>
                                    <?php $__currentLoopData = $labs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($lab->id); ?>" <?php echo e(old('id_ruangan', $detail?->id_ruangan) == $lab->id ? 'selected' : ''); ?>>
                                            <?php echo e($lab->nama_lab); ?> (Kapasitas <?php echo e($lab->kapasitas); ?> orang)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Keperluan</label>
                                <input type="text" name="keperluan" value="<?php echo e(old('keperluan', $reservasi->keperluan)); ?>" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Tanggal Pemakaian</label>
                                <input type="date" name="tanggal_pakai" value="<?php echo e(old('tanggal_pakai', $detail?->tanggal_pakai)); ?>" class="form-control" min="<?php echo e(date('Y-m-d')); ?>" required>
                            </div>

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label small fw-semibold">Jam Mulai</label>
                                    <input type="time" name="jam_mulai" value="<?php echo e(old('jam_mulai')); ?>" 
       class="form-control" id="jam_mulai" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label small fw-semibold">Jam Selesai <span class="text-secondary">(maks. 18:10)</span></label>
                                    <input type="time" name="jam_selesai" value="<?php echo e(old('jam_selesai', \Illuminate\Support\Str::substr($detail?->jam_selesai, 0, 5))); ?>" class="form-control" max="18:10" required>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100 py-2">
                                    <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                                </button>
                                <a href="<?php echo e(route('reservasi.show', $reservasi->id)); ?>" class="btn btn-outline-secondary py-2">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->startPush('scripts'); ?>
<script>
    // Kalau tanggal dipilih = hari ini, set min jam mulai = sekarang
    const tanggalInput = document.querySelector('input[name="tanggal_pakai"]');
    const jamMulaiInput = document.getElementById('jam_mulai');

    function updateMinJam() {
        const today = new Date().toISOString().split('T')[0];
        if (tanggalInput.value === today) {
            const now = new Date();
            const hh = String(now.getHours()).padStart(2, '0');
            const mm = String(now.getMinutes()).padStart(2, '0');
            jamMulaiInput.min = hh + ':' + mm;
        } else {
            jamMulaiInput.min = '';
        }
    }

    tanggalInput.addEventListener('change', updateMinJam);
    updateMinJam(); // jalankan saat halaman pertama dibuka
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\RuangLab\RuangLab\resources\views/reservasi/edit.blade.php ENDPATH**/ ?>