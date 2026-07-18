<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RuangLab') | Sistem Reservasi Laboratorium</title>

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
            --rl-bg: #f5f7fb;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--rl-bg);
            color: #1f2536;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
        .navbar-brand { font-weight: 800; letter-spacing: -0.5px; }
        .navbar-brand span { color: var(--rl-accent); }
        .btn-primary {
            background-color: var(--rl-primary);
            border-color: var(--rl-primary);
        }
        .btn-primary:hover {
            background-color: var(--rl-primary-dark);
            border-color: var(--rl-primary-dark);
        }
        .text-primary-custom { color: var(--rl-primary) !important; }
        .bg-primary-custom { background-color: var(--rl-primary) !important; }
        .hero-gradient {
            background: linear-gradient(135deg, var(--rl-dark) 0%, var(--rl-primary-dark) 55%, var(--rl-primary) 100%);
        }
        .card-lab {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(16, 23, 42, 0.08);
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .card-lab:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(16, 23, 42, 0.14);
        }
        .card-lab .lab-thumb {
            height: 180px;
            object-fit: cover;
            background: linear-gradient(135deg, #dbe4ff, #c2f5ec);
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
        footer { background-color: var(--rl-dark); color: #c7cede; }
        footer a { color: #e5e9f5; text-decoration: none; }
        footer a:hover { color: var(--rl-accent); }
        .rounded-xl { border-radius: 16px; }

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
    </style>

    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary-custom sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">Ruang<span>Lab</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active fw-semibold' : '' }}" href="{{ route('home') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('laboratorium.*') ? 'active fw-semibold' : '' }}" href="{{ route('laboratorium.index') }}">Laboratorium</a>
                </li>
                @auth
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reservasi.*') ? 'active fw-semibold' : '' }}" href="{{ route('reservasi.index') }}">Reservasi Saya</a>
                </li>
                @endauth
            </ul>
            <ul class="navbar-nav align-items-lg-center gap-lg-2">
                @auth
                    @if(auth()->user()->isAdmin())
                    <li class="nav-item">
                        <a class="btn btn-sm btn-outline-light" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard Laboran
                        </a>
                    </li>
                    @elseif(auth()->user()->isAslab())
                    <li class="nav-item">
                        <a class="btn btn-sm btn-outline-light" href="{{ route('aslab.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard Aslab
                        </a>
                    </li>
                    @endif
                    <li class="nav-item">
                        @include('partials.notification-bell')
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->nama }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('reservasi.index') }}">Reservasi Saya</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="bi bi-person me-1"></i>Profil Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Masuk</a></li>
                    <li class="nav-item">
                        <a class="btn btn-light btn-sm fw-semibold" href="{{ route('register') }}">Daftar</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

@include('partials.toast-flash')

<main>
    @yield('content')
</main>

<footer class="mt-5 py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h5 class="fw-bold text-white">Ruang<span class="text-info">Lab</span></h5>
                <p class="small mb-0">Sistem reservasi laboratorium kampus yang mudah, cepat, dan transparan untuk mahasiswa dan dosen.</p>
            </div>
            <div class="col-md-4">
                <h6 class="fw-semibold text-white mb-3">Tautan</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="{{ route('home') }}">Beranda</a></li>
                    <li class="mb-2"><a href="{{ route('laboratorium.index') }}">Daftar Laboratorium</a></li>
                    <li class="mb-2"><a href="{{ route('login') }}">Masuk</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="fw-semibold text-white mb-3">Kontak</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><i class="bi bi-envelope me-2"></i>Admin@iclabs.umi.ac.id</li>
                    <li class="mb-2"><i class="bi bi-telephone me-2"></i>088242980774</li>
                </ul>
            </div>
        </div>
        <hr class="border-secondary my-4">
        <p class="small text-center mb-0">&copy; {{ date('Y') }} RuangLab. Seluruh hak cipta dilindungi.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@include('partials.confirm-modal')
@stack('scripts')
</body>
</html>
