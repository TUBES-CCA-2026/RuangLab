<?php echo csrf_field(); ?>

<?php if($errors->any()): ?>
    <div class="alert alert-danger rounded-3">
        <ul class="mb-0 small">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Nama Mata Kuliah / Praktikum</label>
        <input type="text" name="nama_matkul" value="<?php echo e(old('nama_matkul', $jadwal->mataKuliah->nama_matkul ?? '')); ?>" class="form-control" placeholder="Praktikum Basis Data" required>
    </div>
    <div class="col-md-4">
        <label class="form-label small fw-semibold">Dosen Pengampu</label>
        <input type="text" name="nama_dosen" value="<?php echo e(old('nama_dosen', $jadwal->mataKuliah->nama_dosen ?? '')); ?>" class="form-control" placeholder="Nama dosen" required>
    </div>
    <div class="col-md-2">
        <label class="form-label small fw-semibold">SKS</label>
        <input type="number" name="sks" value="<?php echo e(old('sks', $jadwal->mataKuliah->sks ?? 1)); ?>" class="form-control" min="1" max="10">
    </div>

    <div class="col-md-4">
        <label class="form-label small fw-semibold">Laboratorium</label>
        <select name="id_lab" class="form-select" required>
            <option value="">Pilih Lab</option>
            <?php $__currentLoopData = $labs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($lab->id); ?>" <?php echo e(old('id_lab', $jadwal->id_lab ?? '') == $lab->id ? 'selected' : ''); ?>><?php echo e($lab->nama_lab); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label small fw-semibold">Hari</label>
        <select name="id_day" class="form-select" required>
            <option value="">Pilih Hari</option>
            <?php $__currentLoopData = $hariList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($h->id); ?>" <?php echo e(old('id_day', $jadwal->id_day ?? '') == $h->id ? 'selected' : ''); ?>><?php echo e($h->nama_hari); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label small fw-semibold">Tahun Ajaran</label>
        <select name="id_tahun_ajaran" class="form-select" required>
            <option value="">Pilih Tahun Ajaran</option>
            <?php $__currentLoopData = $tahunAjarans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($ta->id); ?>" <?php echo e(old('id_tahun_ajaran', $jadwal->id_tahun_ajaran ?? '') == $ta->id ? 'selected' : ''); ?>>
                    <?php echo e($ta->nama); ?><?php echo e($ta->is_aktif ? ' (Aktif)' : ''); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label small fw-semibold">Jam Mulai</label>
        <input type="time" name="jam_mulai" value="<?php echo e(old('jam_mulai', isset($jadwal) ? \Illuminate\Support\Str::substr($jadwal->jam_mulai,0,5) : '')); ?>" class="form-control" required>
    </div>
    <div class="col-md-2">
        <label class="form-label small fw-semibold">Jam Selesai</label>
        <input type="time" name="jam_selesai" value="<?php echo e(old('jam_selesai', isset($jadwal) ? \Illuminate\Support\Str::substr($jadwal->jam_selesai,0,5) : '')); ?>" class="form-control" required>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="<?php echo e(route('admin.jadwal-praktikum.index')); ?>" class="btn btn-outline-secondary">Batal</a>
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-lg"></i> Simpan
    </button>
</div>
<?php /**PATH C:\xampp\htdocs\RuangLab\resources\views/admin/jadwal-praktikum/_form.blade.php ENDPATH**/ ?>