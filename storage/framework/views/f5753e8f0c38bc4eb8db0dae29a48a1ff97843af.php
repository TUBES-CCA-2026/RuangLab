

<?php $__env->startSection('title', 'Edit Tahun Ajaran'); ?>
<?php $__env->startSection('page-title', 'Edit Tahun Ajaran'); ?>

<?php $__env->startSection('content'); ?>

<div class="card table-card">
    <div class="card-body p-4">
        <form method="POST" action="<?php echo e(route('admin.tahun-ajaran.update', $tahunAjaran->id)); ?>">
            <?php echo $__env->make('admin.tahun-ajaran._form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\RuangLab\RuangLab\resources\views/admin/tahun-ajaran/edit.blade.php ENDPATH**/ ?>