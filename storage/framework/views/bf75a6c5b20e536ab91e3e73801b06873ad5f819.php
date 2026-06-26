<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> | Aslab RuangLab</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        :root {
            --rl-primary:      #2952e3;
            --rl-primary-dark: #1d3aa8;
            --rl-accent:       #00c2a8;
            --rl-dark:         #10172a;
            --rl-sidebar:      #131b34;
            --rl-bg:           #f3f5fa;
            --rl-aslab:        #0f7a5a;   /* hijau toska khusus aslab */
            --rl-aslab-light:  #e6f7f3;
        }
        body { font-family: 'Poppins', sans-serif; background: var(--rl-bg); }

        /* ─── Sidebar ─── */
        .aslab-sidebar {
            width: 250px;
            min-height: 100vh;
            background: var(--rl-sidebar);
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 1030;
        }
        .aslab-sidebar .brand { color: #fff; font-weight: 800; }
        .aslab-sidebar .brand span { color: var(--rl-accent); }
        .aslab-sidebar .role-badge {
            background: rgba(0,194,168,.15);
            color: var(--rl-accent);
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .5px;
            border-radius: 20px;
            padding: 2px 10px;
        }
        .aslab-sidebar .nav-link {
            color: #aab3cc;
            border-radius: 10px;
            padding: .65rem .9rem;
            font-size: .92rem;
            font-weight: 500;
        }
        .aslab-sidebar .nav-link.active,
        .aslab-sidebar .nav-link:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
        }
        .aslab-sidebar .nav-link i { width: 20px; }
        .aslab-sidebar .nav-section {
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: 1px;
            color: #566080;
            text-transform: uppercase;
            padding: .4rem .9rem;
            margin-top: .5rem;
        }

        /* ─── Content area ─── */
        .aslab-content { margin-left: 250px; min-height: 100vh; }
        .aslab-topbar {
            background: #fff;
            border-bottom: 1px solid #eaedf3;
        }
        .aslab-topbar .role-chip {
            background: var(--rl-aslab-light);
            color: var(--rl-aslab);
            font-size: .75rem;
            font-weight: 600;
            border-radius: 20px;
            padding: 3px 12px;
        }

        /* ─── Cards ─── */
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

        /* ─── Status badges ─── */
        .badge-status-pending      { background-color: #ffb020; }
        .badge-status-disetujui    { background-color: #15b27a; }
        .badge-status-ditolak      { background-color: #e2483d; }
        .badge-status-sedang_dipakai { background-color: var(--rl-primary); }
        .badge-status-hangus       { background-color: #6b7280; }

        /* ─── Priority badge ─── */
        .badge-prioritas {
            background: linear-gradient(135deg, #f59e0b, #ef4444);
            color: #fff;
            font-size: .68rem;
            font-weight: 700;
            border-radius: 20px;
            padding: 2px 8px;
        }

        /* ─── Lab calendar card ─── */
        .lab-status-card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(16,23,42,.06);
        }
        .lab-status-card .lab-header {
            border-radius: 14px 14px 0 0;
            padding: .85rem 1rem;
        }
        .lab-status-card .lab-header.tersedia { background: #f0fdf4; border-left: 4px solid #15b27a; }
        .lab-status-card .lab-header.dipakai  { background: #eff6ff; border-left: 4px solid var(--rl-primary); }
        .jadwal-item {
            background: #f8fafc;
            border-radius: 8px;
            border-left: 3px solid var(--rl-primary);
            padding: .5rem .75rem;
            font-size: .83rem;
        }

        @media (max-width: 991.98px) {
            .aslab-sidebar { left: -250px; transition: left .2s ease; }
            .aslab-sidebar.show { left: 0; }
            .aslab-content { margin-left: 0; }
        }
        .bi-power { 
            color: #e2483d;
            
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

<aside class="aslab-sidebar p-3" id="aslabSidebar">
    <div class="px-2 py-3 mb-1">
        <a href="<?php echo e(route('home')); ?>" class="brand d-flex align-items-center gap-2 text-decoration-none mb-2">
            <i class="bi bi-flask fs-4 text-info"></i>
            <span class="fs-5">Ruang<span>Lab</span></span>
        </a>
        <span class="role-badge"><i class="bi bi-star-fill me-1" style="font-size:.6rem;"></i>Asisten Lab</span>
    </div>

    <nav class="nav flex-column gap-1">
        
        <a href="<?php echo e(route('aslab.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('aslab.dashboard') ? 'active' : ''); ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        
        <a href="<?php echo e(route('aslab.verifikasi.index')); ?>" class="nav-link <?php echo e(request()->routeIs('aslab.verifikasi.*') ? 'active' : ''); ?>">
            <i class="bi bi-clipboard-check"></i> Ajukan Reservasi
        </a>
        <a href="<?php echo e(route('aslab.history.index')); ?>" class="nav-link <?php echo e(request()->routeIs('aslab.history.*') ? 'active' : ''); ?>">
            <i class="bi bi-clock-history"></i> Riwayat
        </a>

        <hr class="border-secondary opacity-25 my-2">
        <a href="<?php echo e(route('profile.show')); ?>" class="nav-link <?php echo e(request()->routeIs('profile.*') ? 'active' : ''); ?>">
            <i class="bi bi-person-circle"></i> Profil Saya
        </a>
        <a href="<?php echo e(route('notifications.index')); ?>" class="nav-link <?php echo e(request()->routeIs('notifications.*') ? 'active' : ''); ?>">
            <i class="bi bi-bell"></i> Notifikasi
            <?php $unread = auth()->user()->unreadNotifications->count(); ?>
            <?php if($unread > 0): ?>
                <span class="badge bg-danger ms-1" style="font-size:.65rem;"><?php echo e($unread); ?></span>
            <?php endif; ?>
        </a>
        <a href="<?php echo e(route('home')); ?>" class="nav-link">
            <i class="bi bi-box-arrow-left"></i> Lihat Situs Publik
        </a>
        <form action="<?php echo e(route('logout')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent text-danger">
                <i class="bi bi-power"></i> Keluar
            </button>
        </form>
    </nav>
</aside>

<div class="aslab-content">
    <header class="aslab-topbar d-flex align-items-center justify-content-between px-4 py-3">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-light d-lg-none" onclick="document.getElementById('aslabSidebar').classList.toggle('show')">
                <i class="bi bi-list fs-5"></i>
            </button>
            <h5 class="mb-0 fw-semibold"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h5>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="role-chip d-none d-sm-inline"><i class="bi bi-star-fill me-1" style="font-size:.6rem;"></i>Asisten Lab</span>
            <a href="<?php echo e(route('notifications.index')); ?>" class="btn btn-light btn-sm position-relative">
                <i class="bi bi-bell fs-5"></i>
                <?php $unread = auth()->user()->unreadNotifications->count(); ?>
                <?php if($unread > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:.6rem;"><?php echo e($unread); ?></span>
                <?php endif; ?>
            </a>
            <a href="<?php echo e(route('profile.show')); ?>" class="d-flex align-items-center gap-2 text-decoration-none text-dark">
                <i class="bi bi-person-circle fs-5 text-secondary"></i>
                <span class="fw-medium"><?php echo e(auth()->user()->nama); ?></span>
            </a>
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
<?php /**PATH C:\xampp\htdocs\RuangLab\RuangLab\resources\views/layouts/aslab.blade.php ENDPATH**/ ?>