<?php echo csrf_field(); ?>
<?php if(isset($tahunAjaran)): ?>
    <?php echo method_field('PUT'); ?>
<?php endif; ?>

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Tahun Ajaran</label>
        <input type="text" name="tahun_ajaran" value="<?php echo e(old('tahun_ajaran', $tahunAjaran->tahun_ajaran ?? '')); ?>"
               class="form-control" placeholder="Contoh: 2025/2026" required>
    </div>
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Semester</label>
        <select name="semester" class="form-select" required>
            <option value="">Pilih Semester</option>
            <option value="ganjil" <?php echo e(old('semester', $tahunAjaran->semester ?? '') === 'ganjil' ? 'selected' : ''); ?>>Ganjil</option>
            <option value="genap" <?php echo e(old('semester', $tahunAjaran->semester ?? '') === 'genap' ? 'selected' : ''); ?>>Genap</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Tanggal Mulai</label>
        <input type="date" name="tanggal_mulai" value="<?php echo e(old('tanggal_mulai', optional($tahunAjaran->tanggal_mulai ?? null)->format('Y-m-d'))); ?>"
               class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Tanggal Selesai</label>
        <input type="date" name="tanggal_selesai" value="<?php echo e(old('tanggal_selesai', optional($tahunAjaran->tanggal_selesai ?? null)->format('Y-m-d'))); ?>"
               class="form-control" required>
    </div>
    <div class="col-12">
        <div class="form-check">
            <input type="hidden" name="is_aktif" value="0">
            <input type="checkbox" name="is_aktif" id="is_aktif" value="1" class="form-check-input"
                   <?php echo e(old('is_aktif', $tahunAjaran->is_aktif ?? false) ? 'checked' : ''); ?>>
            <label class="form-check-label small" for="is_aktif">Jadikan periode aktif saat ini</label>
        </div>
        <div class="form-text">Hanya satu periode yang bisa aktif; mengaktifkan ini akan menonaktifkan periode aktif lainnya.</div>
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Simpan</button>
    <a href="<?php echo e(route('admin.tahun-ajaran.index')); ?>" class="btn btn-outline-secondary">Batal</a>
</div>
<?php /**PATH D:\RuangLab\RuangLab\resources\views/admin/tahun-ajaran/_form.blade.php ENDPATH**/ ?>