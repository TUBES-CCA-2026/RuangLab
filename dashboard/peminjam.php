<?php
// dashboard/peminjam.php

require_once '../config/auth.php';
requireRole('peminjam');   // Hanya role peminjam yang boleh masuk

$user = authUser();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Peminjam — RuangLab</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<!-- ── Sidebar ──────────────────────────────────────────── -->
<aside class="sidebar">
    <a href="#" class="sidebar-logo">
        <div class="sidebar-logo-icon"><i class="ti ti-flask"></i></div>
        <div>
            <div class="sidebar-logo-text">RuangLab</div>
            <div class="sidebar-logo-sub">ICo LABS-UMI</div>
        </div>
    </a>

    <nav class="sidebar-nav">
        <div class="sidebar-section-label">Menu</div>
        <a href="peminjam.php" class="nav-item active">
            <i class="ti ti-layout-dashboard"></i> Dashboard
        </a>
        <a href="#" class="nav-item">
            <i class="ti ti-door"></i> Lihat Ruangan
        </a>
        <a href="#" class="nav-item">
            <i class="ti ti-calendar-event"></i> Reservasi Saya
            <span class="nav-badge">2</span>
        </a>
        <a href="#" class="nav-item">
            <i class="ti ti-qrcode"></i> Check-in QR
        </a>
        <a href="#" class="nav-item">
            <i class="ti ti-history"></i> Riwayat
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-avatar"><?= getInitials($user['nama']) ?></div>
        <div>
            <div class="sidebar-user-name"><?= htmlspecialchars($user['nama']) ?></div>
            <div class="sidebar-user-role">
                <span class="badge badge-primary">Peminjam</span>
            </div>
        </div>
        <a href="../logout.php" class="sidebar-logout" title="Logout">
            <i class="ti ti-logout"></i>
        </a>
    </div>
</aside>

<!-- ── Main Content ──────────────────────────────────────── -->
<div class="main-wrapper">

    <!-- Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Selamat datang, <?= htmlspecialchars(explode(' ', $user['nama'])[0]) ?> 👋</h1>
            <p style="font-size:13px; color:var(--text-secondary); margin-top:2px;">
                <?= date('l, d F Y') ?>
            </p>
        </div>
        <span class="badge badge-primary" style="font-size:13px; padding:6px 14px;">
            <i class="ti ti-user" style="margin-right:4px;"></i> Peminjam
        </span>
    </div>

    <!-- Content -->
    <div class="page-content">

        <!-- Stats -->
        <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:28px;">
            <?php
            $stats = [
                ['icon'=>'ti-calendar-event', 'value'=>'2', 'label'=>'Reservasi Aktif',     'color'=>'var(--primary)'],
                ['icon'=>'ti-clock',           'value'=>'1', 'label'=>'Menunggu Verifikasi', 'color'=>'var(--warning)'],
                ['icon'=>'ti-circle-check',    'value'=>'8', 'label'=>'Reservasi Selesai',   'color'=>'var(--success)'],
                ['icon'=>'ti-star',            'value'=>'5.0','label'=>'Skor Reputasi',      'color'=>'var(--primary)'],
            ];
            foreach ($stats as $s): ?>
            <div class="card">
                <div class="card-body" style="display:flex; align-items:center; gap:16px;">
                    <div style="width:48px;height:48px;border-radius:var(--radius-md);background:var(--primary-light);display:flex;align-items:center;justify-content:center;">
                        <i class="ti <?= $s['icon'] ?>" style="font-size:22px;color:<?= $s['color'] ?>;"></i>
                    </div>
                    <div>
                        <div style="font-size:26px;font-weight:700;color:<?= $s['color'] ?>;"><?= $s['value'] ?></div>
                        <div style="font-size:12px;color:var(--text-secondary);"><?= $s['label'] ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Coming soon -->
        <div class="card">
            <div class="card-body" style="text-align:center; padding:48px;">
                <i class="ti ti-tools" style="font-size:48px;color:var(--primary);margin-bottom:12px;display:block;"></i>
                <h3 style="font-size:18px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">
                    Halaman dalam Pengembangan
                </h3>
                <p style="font-size:14px;color:var(--text-secondary);">
                    Fitur daftar ruangan, reservasi, dan check-in akan segera hadir.
                </p>
            </div>
        </div>

    </div>
</div>

</body>
</html>
