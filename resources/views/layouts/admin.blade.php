<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | Laboran RuangLab</title>

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
        .badge-status-selesai { background-color: #0d6efd; }

        .rl-toast-container { z-index: 1080; }
        .rl-toast { border-radius: 12px; color: #fff; min-width: 280px; box-shadow: 0 10px 30px rgba(16,23,42,.18); }
        .rl-toast .btn-close { filter: invert(1) grayscale(100%) brightness(200%); opacity: .8; }
        .rl-toast-success { background: linear-gradient(135deg, #15b27a, #0e8f61); }
        .rl-toast-error { background: linear-gradient(135deg, #e2483d, #c53328); }
        .rl-notif-dropdown .dropdown-toggle::after { display: none; }
        .rl-notif-panel { width: 340px; max-width: 90vw; border: none; border-radius: 14px; box-shadow: 0 12px 32px rgba(16,23,42,.15); overflow: hidden; }
        .rl-notif-list { max-height: 320px; overflow-y: auto; }
        .rl-notif-item { padding: .65rem .9rem; white-space: normal; border-bottom: 1px solid #f1f2f6; }
        .rl-notif-item:last-child { border-bottom: none; }
        .rl-notif-unread { background-color: rgba(41,82,227,.06); }
        .rl-notif-item:hover { background-color: #f6f7fb; }
        @media (max-width: 991.98px) {
            .admin-sidebar { left: -250px; transition: left .2s ease; }
            .admin-sidebar.show { left: 0; }
            .admin-content { margin-left: 0; }
        }
        .bi-power{
            color:#e2483d;
        }
    </style>
    @stack('styles')
</head>
<body>

<aside class="admin-sidebar p-3" id="adminSidebar">
    <a href="{{ route('admin.dashboard') }}" class="brand d-flex align-items-center gap-2 text-decoration-none px-2 py-3 mb-2">
        <i class="bi bi-flask fs-4 text-info"></i>
        <span class="fs-5">Ruang<span>Lab</span></span>
    </a>
    <nav class="nav flex-column gap-1">
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('admin.laboratorium.index') }}" class="nav-link {{ request()->routeIs('admin.laboratorium.*') ? 'active' : '' }}">
            <i class="bi bi-building"></i> Laboratorium
        </a>
        <a href="{{ route('admin.reservasi.index') }}" class="nav-link {{ request()->routeIs('admin.reservasi.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i> Reservasi
        </a>
        <a href="{{ route('admin.jadwal-praktikum.index') }}" class="nav-link {{ request()->routeIs('admin.jadwal-praktikum.*') ? 'active' : '' }}">
            <i class="bi bi-calendar2-week"></i> Jadwal Praktikum
        </a>
        <a href="{{ route('admin.jadwal.import') }}" class="nav-link {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-arrow-up"></i> Import Jadwal
        </a>
        <a href="{{ route('admin.tahun-ajaran.index') }}" class="nav-link {{ request()->routeIs('admin.tahun-ajaran.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-range"></i> Tahun Ajaran
        </a>
        <a href="{{ route('admin.history.index') }}" class="nav-link {{ request()->routeIs('admin.history.*') ? 'active' : '' }}">
    <i class="bi bi-clock-history"></i> History Reservasi
</a>
        <a href="{{ route('admin.rekap.index') }}" class="nav-link {{ request()->routeIs('admin.rekap.*') ? 'active' : '' }}">
            <i class="bi bi-graph-up"></i> Rekap
        </a>
        <a href="{{ route('admin.user.index') }}" class="nav-link {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Pengguna
        </a>
        <hr class="border-secondary opacity-25 my-2">
        <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i> Profil Saya
        </a>
        <a href="{{ route('notifications.index') }}" class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
            <i class="bi bi-bell"></i> Notifikasi
            @php $unread = auth()->user()->unreadNotifications->count(); @endphp
            @if($unread > 0)
                <span class="badge bg-danger ms-1" style="font-size:.65rem;">{{ $unread }}</span>
            @endif
        </a>
        <a href="{{ route('home') }}" class="nav-link">
            <i class="bi bi-box-arrow-left"></i> Lihat Situs Publik
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent text-danger">
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
            <h5 class="mb-0 fw-semibold">@yield('page-title', 'Dashboard')</h5>
        </div>
        <div class="d-flex align-items-center gap-2">
            @include('partials.notification-bell')
            <a href="{{ route('profile.show') }}" class="d-flex align-items-center gap-2 text-decoration-none text-dark">
                <i class="bi bi-person-circle fs-5 text-secondary"></i>
                <span class="fw-medium">{{ auth()->user()->nama }}</span>
            </a>
        </div>
    </header>

    @include('partials.toast-flash')

    <div class="p-4">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
