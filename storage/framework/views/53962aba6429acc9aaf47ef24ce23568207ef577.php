<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> | Laboran RuangLab</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        :root {
            --rl-primary: #2952e3;
            --rl-primary-dark: #1d3aa8;
            --rl-accent: #00c2a8;
            --rl-dark: #10172a;
            --rl-sidebar: #131b34;
            --rl-bg: #f3f5fa;
        }
        body { font-family: 'Poppins', sans-serif; background: var(--rl-bg); }
        .admin-sidebar {
            width: 250px;
            min-height: 100vh;
            background: var(--rl-sidebar);
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 1030;
        }
        .admin-sidebar .brand { color: #fff; font-weight: 800; }
        .admin-sidebar .brand span { color: var(--rl-accent); }
        .admin-sidebar .nav-link {
            color: #aab3cc;
            border-radius: 10px;
            padding: .65rem .9rem;
            font-size: .92rem;
            font-weight: 500;
        }
        .admin-sidebar .nav-link.active,
        .admin-sidebar .nav-link:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
        }
        .admin-sidebar .nav-link i { width: 20px; }
        .admin-content { margin-left: 250px; min-height: 100vh; }
        .admin-topbar {
            background: #fff;
            border-bottom: 1px solid #eaedf3;
        }
        .stat-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(16,23,42,.06);
        }
        .table-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(16,23,42,.06);
        }
        .badge-status-pending { background-color: #ffb020; }
        .badge-status-disetujui { background-color: #15b27a; }
        .badge-status-ditolak { background-color: #e2483d; }
        .badge-status-sedang_dipakai { background-color: var(--rl-primary); }
        .badge-status-hangus { background-color: #6b7280; }
        @media (max-width: 991.98px) {
            .admin-sidebar { left: -250px; transition: left .2s ease; }
            .admin-sidebar.show { left: 0; }
            .admin-content { margin-left: 0; }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

<aside class="admin-sidebar p-3" id="adminSidebar">
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="brand d-flex align-items-center gap-2 text-decoration-none px-2 py-3 mb-2">
        <i class="bi bi-flask fs-4 text-info"></i>
        <span class="fs-5">Ruang<span>Lab</span></span>
    </a>
    <nav class="nav flex-column gap-1">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="<?php echo e(route('admin.laboratorium.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.laboratorium.*') ? 'active' : ''); ?>">
            <i class="bi bi-building"></i> Laboratorium
        </a>
        <a href="<?php echo e(route('admin.reservasi.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.reservasi.*') ? 'active' : ''); ?>">
            <i class="bi bi-calendar-check"></i> Reservasi
        </a>
        <hr class="border-secondary opacity-25 my-2">
        <a href="<?php echo e(route('home')); ?>" class="nav-link">
            <i class="bi bi-box-arrow-left"></i> Lihat Situs Publik
        </a>
        <form action="<?php echo e(route('logout')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent">
                <i class="bi bi-power"></i> Keluar
            </button>
        </form>
    </nav>
</aside>

<div class="admin-content">
    <header class="admin-topbar d-flex align-items-center justify-content-between px-4 py-3">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-light d-lg-none" onclick="document.getElementById('adminSidebar').classList.toggle('show')">
                <i class="bi bi-list fs-5"></i>
            </button>
            <h5 class="mb-0 fw-semibold"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h5>
        </div>
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-person-circle fs-5 text-secondary"></i>
            <span class="fw-medium"><?php echo e(auth()->user()->nama); ?></span>
        </div>
    </header>

    <div class="p-4">
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                <i class="bi bi-check-circle me-1"></i> <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                <i class="bi bi-x-circle me-1"></i> <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\RuangLab\RuangLab\resources\views/layouts/admin.blade.php ENDPATH**/ ?>