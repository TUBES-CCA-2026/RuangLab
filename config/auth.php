<?php
// config/auth.php
// Sertakan file ini di BARIS PERTAMA setiap halaman yang dilindungi

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Pastikan user sudah login.
 * Kalau belum → redirect ke login.php
 */
function requireLogin() {
    if (!isset($_SESSION['id_user']) || !isset($_SESSION['role'])) {
        header('Location: ' . getBaseUrl() . '/login.php');
        exit();
    }
}

/**
 * Pastikan user punya role tertentu.
 * Kalau role tidak cocok → redirect ke halaman unauthorized
 *
 * @param string|array $allowedRoles  contoh: 'aslab' atau ['aslab','laboran']
 */
function requireRole($allowedRoles) {
    requireLogin();

    $allowed = (array) $allowedRoles;

    if (!in_array($_SESSION['role'], $allowed)) {
        header('Location: ' . getBaseUrl() . '/unauthorized.php');
        exit();
    }
}

/**
 * Ambil base URL project (untuk redirect lintas folder)
 */
function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host     = $_SERVER['HTTP_HOST'];
    // Ambil path sampai folder ruanglab
    $path = dirname(dirname($_SERVER['SCRIPT_NAME']));
    return rtrim($protocol . '://' . $host . $path, '/');
}

/**
 * Shortcut ambil data session
 */
function authUser() {
    return [
        'id'   => $_SESSION['id_user'] ?? null,
        'nama' => $_SESSION['nama']    ?? 'User',
        'role' => $_SESSION['role']    ?? null,
    ];
}

/**
 * Ambil inisial dari nama (untuk avatar)
 * contoh: "Ahmad Fauzan" → "AF"
 */
function getInitials(string $name): string {
    $words    = explode(' ', trim($name));
    $initials = '';
    foreach (array_slice($words, 0, 2) as $word) {
        $initials .= strtoupper($word[0]);
    }
    return $initials;
}
