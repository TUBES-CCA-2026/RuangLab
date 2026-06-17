<?php
// login.php

session_start();
require_once 'config/database.php';

// ── Kalau sudah login, langsung redirect ke dashboard ──────
if (isset($_SESSION['id_user']) && isset($_SESSION['role'])) {
    header('Location: dashboard/' . $_SESSION['role'] . '.php');
    exit();
}

// ── Proses form login ───────────────────────────────────────
$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validasi input kosong
    if (empty($email) || empty($password)) {
        $error = 'Email dan password wajib diisi.';
    } else {
        $conn = getConnection();

        // Cari user berdasarkan email
        $stmt = $conn->prepare(
            "SELECT id_user, nama, email, password, role, status
             FROM mst_users
             WHERE email = ?
             AND deleted_at IS NULL
             LIMIT 1"
        );
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user   = $result->fetch_assoc();
        $stmt->close();
        $conn->close();

        if (!$user) {
            // Email tidak ditemukan
            $error = 'Email tidak terdaftar di sistem.';
        } elseif (!password_verify($password, $user['password'])) {
            // Password salah
            $error = 'Password yang kamu masukkan salah.';
        } elseif ($user['status'] != 1) {
            // Akun dinonaktifkan
            $error = 'Akun kamu telah dinonaktifkan. Hubungi Laboran untuk informasi lebih lanjut.';
        } else {
            // ✅ Login berhasil — simpan ke session
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nama']    = $user['nama'];
            $_SESSION['email']   = $user['email'];
            $_SESSION['role']    = $user['role'];

            // Redirect sesuai role
            header('Location: dashboard/' . $user['role'] . '.php');
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — RuangLab</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Tabler Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <!-- Inter font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ── Login page specific ── */
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: var(--surface);
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 24px 16px;
        }

        .login-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            padding: 36px 32px;
        }

        /* Logo */
        .login-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 28px;
        }
        .logo-icon-wrap {
            width: 52px;
            height: 52px;
            background: var(--primary-light);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: var(--primary);
            margin-bottom: 12px;
        }
        .logo-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-primary);
        }
        .logo-sub {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 3px;
            text-align: center;
        }

        /* Password toggle */
        #toggle-pass { cursor: pointer; }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">

        <!-- Logo -->
        <div class="login-logo">
            <div class="logo-icon-wrap">
                <i class="ti ti-flask"></i>
            </div>
            <div class="logo-title">RuangLab</div>
            <div class="logo-sub">Sistem Penjadwalan &amp; Reservasi Lab · UMI</div>
        </div>

        <!-- Alert error -->
        <?php if ($error): ?>
        <div class="alert alert-danger">
            <i class="ti ti-alert-circle"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
        <?php endif; ?>

        <!-- Alert success (dari register) -->
        <?php if (isset($_GET['registered'])): ?>
        <div class="alert alert-success">
            <i class="ti ti-circle-check"></i>
            <span>Registrasi berhasil! Silakan login dengan akun kamu.</span>
        </div>
        <?php endif; ?>

        <!-- Form Login -->
        <form method="POST" action="login.php" novalidate>

            <!-- Email -->
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <div class="input-wrapper">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control <?= ($error && !empty($_POST['email'])) ? '' : ($error ? 'is-invalid' : '') ?>"
                        placeholder="Masukkan email kamu"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        autocomplete="email"
                        required
                    >
                    <i class="ti ti-mail input-icon-right" style="pointer-events:none;"></i>
                </div>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                    >
                    <button
                        type="button"
                        id="toggle-pass"
                        class="input-icon-right"
                        onclick="togglePassword()"
                        title="Tampilkan/sembunyikan password"
                    >
                        <i class="ti ti-eye" id="eye-icon"></i>
                    </button>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top:8px;">
                <i class="ti ti-login"></i>
                Masuk
            </button>

        </form>

        <!-- Divider -->
        <div class="divider">atau</div>

        <!-- Link Register -->
        <div style="text-align:center; font-size:13px; color: var(--text-secondary);">
            Belum punya akun?
            <a href="register.php" style="color: var(--primary); font-weight:500; text-decoration:none;">
                Daftar di sini
            </a>
        </div>

    </div><!-- /login-card -->

    <!-- Credit -->
    <div style="text-align:center; margin-top:20px; font-size:12px; color: var(--text-secondary);">
        &copy; <?= date('Y') ?> RuangLab · ICo LABS-UMI Makassar
    </div>
</div>

<script>
function togglePassword() {
    const pw   = document.getElementById('password');
    const icon = document.getElementById('eye-icon');
    if (pw.type === 'password') {
        pw.type = 'text';
        icon.className = 'ti ti-eye-off';
    } else {
        pw.type = 'password';
        icon.className = 'ti ti-eye';
    }
}

// Fokus otomatis ke field email saat halaman dimuat
document.getElementById('email').focus();
</script>

</body>
</html>
