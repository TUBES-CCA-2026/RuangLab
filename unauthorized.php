<?php
session_start();
$role = $_SESSION['role'] ?? 'tidak diketahui';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Akses Ditolak — RuangLab</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body{display:flex;align-items:center;justify-content:center;min-height:100vh;background:var(--surface);}
        .box{background:white;border:1px solid var(--border);border-radius:var(--radius-lg);padding:48px;text-align:center;max-width:420px;box-shadow:var(--shadow-md);}
    </style>
</head>
<body>
<div class="box">
    <i class="ti ti-lock" style="font-size:56px;color:var(--primary);display:block;margin-bottom:16px;"></i>
    <h2 style="font-size:22px;font-weight:600;margin-bottom:8px;">Akses Ditolak</h2>
    <p style="color:var(--text-secondary);margin-bottom:16px;">Kamu tidak memiliki izin untuk mengakses halaman ini.</p>
    <div class="alert alert-info" style="text-align:left;">
        <i class="ti ti-info-circle"></i>
        <span>Role kamu: <strong><?= htmlspecialchars($role) ?></strong></span>
    </div>
    <a href="javascript:history.back()" class="btn btn-primary btn-block" style="margin-top:8px;">
        <i class="ti ti-arrow-left"></i> Kembali
    </a>
</div>
</body>
</html>
