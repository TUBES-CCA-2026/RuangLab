<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>History Reservasi — Aslab</title>
</head>
<body>

<h1>History Reservasi</h1>

<table border="1" width="100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Peminjam</th>
            <th>Laboratorium</th>
            <th>Status</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $reservasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><?php echo e($loop->iteration); ?></td>
            <td><?php echo e($item->user->nama ?? '-'); ?></td>
           <td><?php echo e($item->detail->first()->laboratorium->nama_lab ?? '-'); ?></td>
            <td><?php echo e($item->status); ?></td>
            <td><?php echo e($item->created_at->format('d M Y')); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
            <td colspan="5" align="center">Belum ada history</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php echo e($reservasis->links()); ?>


</body>
</html><?php /**PATH D:\RuangLab\resources\views/aslab/history/index.blade.php ENDPATH**/ ?>