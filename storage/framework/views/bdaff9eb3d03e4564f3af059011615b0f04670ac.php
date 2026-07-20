<?php $__env->startSection('title', 'Jadwal Praktikum'); ?>
<?php $__env->startSection('page-title', 'Jadwal Praktikum'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <form method="GET" class="d-flex flex-wrap gap-2">
        <?php if($lihatTerhapus): ?>
            <input type="hidden" name="terhapus" value="1">
        <?php endif; ?>
        <select name="id_day" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Hari</option>
            <?php $__currentLoopData = $hariList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($h->id); ?>" <?php echo e(request('id_day') == $h->id ? 'selected' : ''); ?>><?php echo e($h->nama_hari); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select name="id_lab" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Lab</option>
            <?php $__currentLoopData = $labs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($lab->id); ?>" <?php echo e(request('id_lab') == $lab->id ? 'selected' : ''); ?>><?php echo e($lab->nama_lab); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select name="id_tahun_ajaran" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Tahun Ajaran</option>
            <?php $__currentLoopData = $tahunAjarans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($ta->id); ?>" <?php echo e($tahunAjaranTerpilih == $ta->id ? 'selected' : ''); ?>><?php echo e($ta->nama); ?><?php echo e($ta->is_aktif ? ' (Aktif)' : ''); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </form>
    <div class="d-flex gap-2">
        <?php if($lihatTerhapus): ?>
            <a href="<?php echo e(route('admin.jadwal-praktikum.index', request()->except('terhapus'))); ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Jadwal Aktif
            </a>
        <?php else: ?>
            <a href="<?php echo e(route('admin.jadwal-praktikum.index', array_merge(request()->query(), ['terhapus' => 1]))); ?>" class="btn btn-outline-secondary">
                <i class="bi bi-trash3"></i> Jadwal Terhapus
            </a>
            <a href="<?php echo e(route('admin.jadwal-praktikum.export', request()->query())); ?>" class="btn btn-success">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export Excel
            </a>
            <a href="<?php echo e(route('admin.jadwal.import')); ?>" class="btn btn-outline-primary">
                <i class="bi bi-file-earmark-arrow-up me-1"></i> Import Jadwal
            </a>
            <a href="<?php echo e(route('admin.jadwal-praktikum.create')); ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Tambah Jadwal
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if($lihatTerhapus): ?>
<div class="alert alert-secondary small rounded-3">
    <i class="bi bi-info-circle me-1"></i> Menampilkan jadwal yang sudah dihapus. Jadwal masih tersimpan dan bisa dikembalikan kapan saja.
</div>
<?php endif; ?>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Laboratorium</th>
                    <th>Mata Kuliah</th>
                    <th>Dosen</th>
                    <th>Tahun Ajaran</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $jadwals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="fw-semibold"><?php echo e($jd->hari->nama_hari ?? '-'); ?></td>
                    <td><?php echo e(\Illuminate\Support\Str::substr($jd->jam_mulai,0,5)); ?> – <?php echo e(\Illuminate\Support\Str::substr($jd->jam_selesai,0,5)); ?></td>
                    <td><?php echo e($jd->laboratorium->nama_lab ?? '-'); ?></td>
                    <td><?php echo e($jd->mataKuliah->nama_matkul ?? '-'); ?></td>
                    <td><?php echo e($jd->mataKuliah->nama_dosen ?? '-'); ?></td>
                    <td><?php echo e($jd->tahunAjaran->nama ?? '-'); ?></td>
                    <td class="text-end">
                        <?php if($lihatTerhapus): ?>
                            <form action="<?php echo e(route('admin.jadwal-praktikum.restore', $jd->id)); ?>" method="POST" class="d-inline"
                                  data-confirm="Kembalikan jadwal ini?" data-confirm-variant="success" data-confirm-icon="bi-arrow-counterclockwise" data-confirm-label="Ya, Kembalikan">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-arrow-counterclockwise"></i> Pulihkan
                                </button>
                            </form>
                            <form action="<?php echo e(route('admin.jadwal-praktikum.forceDelete', $jd->id)); ?>" method="POST" class="d-inline"
                                  data-confirm="Hapus permanen? Data tidak bisa dikembalikan!" data-confirm-label="Ya, Hapus Permanen">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="<?php echo e(route('admin.jadwal-praktikum.edit', $jd->id)); ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="<?php echo e(route('admin.jadwal-praktikum.destroy', $jd->id)); ?>" method="POST" class="d-inline" data-confirm="Yakin ingin menghapus jadwal ini?">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center text-secondary py-4">
                        <?php echo e($lihatTerhapus ? 'Tidak ada jadwal yang terhapus.' : 'Belum ada jadwal praktikum.'); ?>

                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\RuangLab\resources\views/admin/jadwal-praktikum/index.blade.php ENDPATH**/ ?>