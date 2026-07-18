<?php $__env->startSection('title', 'Edit Mata Kuliah'); ?>
<?php $__env->startSection('page-title', 'Edit Mata Kuliah'); ?>

<?php $__env->startSection('content'); ?>

<div class="card table-card">
    <div class="card-body p-4">
        <form method="POST" action="<?php echo e(route('admin.mata-kuliah.update', $matkul->id)); ?>">
            <?php echo $__env->make('admin.mata-kuliah._form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\RuangLab\resources\views/admin/mata-kuliah/edit.blade.php ENDPATH**/ ?>