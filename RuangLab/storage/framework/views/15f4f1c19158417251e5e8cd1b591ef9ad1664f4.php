<?php $__env->startSection('title', 'Edit Laboratorium'); ?>
<?php $__env->startSection('page-title', 'Edit Laboratorium'); ?>

<?php $__env->startSection('content'); ?>

<div class="card table-card">
    <div class="card-body p-4">
        <form method="POST" action="<?php echo e(route('admin.laboratorium.update', $lab->id)); ?>" enctype="multipart/form-data">
            <?php echo $__env->make('admin.laboratorium._form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\RuangLab-desain-web\RuangLab\resources\views/admin/laboratorium/edit.blade.php ENDPATH**/ ?>