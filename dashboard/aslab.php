<?php
require_once '../config/auth.php';
requireRole('aslab');
$user = authUser();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Aslab — RuangLab</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<aside class="sidebar">
    <a href="#" class="sidebar-logo">
        <div class="sidebar-logo-icon"><i class="ti ti-flask"></i></div>
        <div><div class="sidebar-logo-text">RuangLab</div><div class="sidebar-logo-sub">ICo LABS-UMI</div></div>
    </a>
    <nav class="sidebar-nav">
        <div class="sidebar-section-label">Menu</div>
        <a href="aslab.php" class="nav-item active"><i class="ti ti-layout-dashboard"></i> Dashboard</a>
        <a href="#" class="nav-item"><i class="ti ti-clipboard-list"></i> Antrian Verifikasi <span class="nav-badge">3</span></a>
        <a href="#" class="nav-item"><i class="ti ti-calendar-plus"></i> Submit Reservasi</a>
        <a href="#" class="nav-item"><i class="ti ti-clock-check"></i> Pantau Check-in</a>
    </nav>
    <div class="sidebar-footer">
        <div class="sidebar-avatar" style="background:var(--warning-light);color:var(--warning);"><?= getInitials($user['nama']) ?></div>
        <div>
            <div class="sidebar-user-name"><?= htmlspecialchars($user['nama']) ?></div>
            <div class="sidebar-user-role"><span class="badge badge-warning">Aslab</span></div>
        </div>
        <a href="../logout.php" class="sidebar-logout"><i class="ti ti-logout"></i></a>
    </div>
</aside>
<div class="main-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Dashboard Aslab</h1>
            <p style="font-size:13px;color:var(--text-secondary);"><?= htmlspecialchars($user['nama']) ?> · <?= date('d F Y') ?></p>
        </div>
        <span class="badge badge-warning" style="font-size:13px;padding:6px 14px;"><i class="ti ti-user-check" style="margin-right:4px;"></i> Aslab</span>
    </div>
    <div class="page-content">
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:28px;">
            <div class="card"><div class="card-body" style="display:flex;align-items:center;gap:16px;">
                <div style="width:48px;height:48px;border-radius:var(--radius-md);background:var(--warning-light);display:flex;align-items:center;justify-content:center;">
                    <i class="ti ti-clock" style="font-size:22px;color:var(--warning);"></i>
                </div>
                <div><div style="font-size:26px;font-weight:700;color:var(--warning);">3</div><div style="font-size:12px;color:var(--text-secondary);">Antrian Pending</div></div>
            </div></div>
            <div class="card"><div class="card-body" style="display:flex;align-items:center;gap:16px;">
                <div style="width:48px;height:48px;border-radius:var(--radius-md);background:var(--success-light);display:flex;align-items:center;justify-content:center;">
                    <i class="ti ti-circle-check" style="font-size:22px;color:var(--success);"></i>
                </div>
                <div><div style="font-size:26px;font-weight:700;color:var(--success);">7</div><div style="font-size:12px;color:var(--text-secondary);">Diproses Hari Ini</div></div>
            </div></div>
            <div class="card"><div class="card-body" style="display:flex;align-items:center;gap:16px;">
                <div style="width:48px;height:48px;border-radius:var(--radius-md);background:var(--primary-light);display:flex;align-items:center;justify-content:center;">
                    <i class="ti ti-chart-bar" style="font-size:22px;color:var(--primary);"></i>
                </div>
                <div><div style="font-size:26px;font-weight:700;color:var(--primary);">142</div><div style="font-size:12px;color:var(--text-secondary);">Total Reservasi</div></div>
            </div></div>
        </div>
        <div class="card"><div class="card-body" style="text-align:center;padding:48px;">
            <i class="ti ti-tools" style="font-size:48px;color:var(--warning);margin-bottom:12px;display:block;"></i>
            <h3 style="font-size:18px;font-weight:600;color:var(--text-primary);margin-bottom:8px;">Halaman dalam Pengembangan</h3>
            <p style="font-size:14px;color:var(--text-secondary);">Fitur antrian verifikasi akan segera hadir.</p>
        </div></div>
    </div>
</div>
</body>
</html>
