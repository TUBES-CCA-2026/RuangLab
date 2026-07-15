@php
    $navUser = auth()->user();
    $navUnreadCount = $navUser->unreadNotifications()->count();
    $navLatestNotifs = $navUser->notifications()->latest()->limit(5)->get();
@endphp
<div class="dropdown rl-notif-dropdown">
    <button class="btn btn-light btn-sm position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-bell fs-5"></i>
        @if($navUnreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger rl-notif-badge" style="font-size:.6rem;">{{ $navUnreadCount > 9 ? '9+' : $navUnreadCount }}</span>
        @endif
    </button>
    <div class="dropdown-menu dropdown-menu-end rl-notif-panel p-0">
        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
            <span class="fw-semibold small">Notifikasi</span>
            @if($navUnreadCount > 0)
                <form method="POST" action="{{ route('notifications.markAllRead') }}" class="m-0">
                    @csrf
                    <button class="btn btn-link btn-sm p-0 text-decoration-none" style="font-size:.75rem;">Tandai semua dibaca</button>
                </form>
            @endif
        </div>
        <div class="rl-notif-list">
            @forelse($navLatestNotifs as $notif)
                @php
                    $ndata = $notif->data;
                    $nstatus = $ndata['status'] ?? '';
                    $nicon = $nstatus === 'disetujui' ? 'bi-check-circle-fill text-success'
                        : ($nstatus === 'ditolak' ? 'bi-x-circle-fill text-danger'
                        : ($nstatus === 'hangus' ? 'bi-clock-history text-secondary'
                        : 'bi-info-circle-fill text-primary'));
                    $nlink = '#';
                    if (!empty($ndata['reservasi_id'])) {
                        if ($navUser->isAdmin()) {
                            $nlink = route('admin.reservasi.show', $ndata['reservasi_id']);
                        } elseif ($navUser->isAslab()) {
                            $nlink = route('aslab.reservasi.show', $ndata['reservasi_id']);
                        } else {
                            $nlink = route('reservasi.show', $ndata['reservasi_id']);
                        }
                    }
                @endphp
                <a href="{{ $nlink }}" class="dropdown-item d-flex gap-2 align-items-start rl-notif-item {{ $notif->read_at ? '' : 'rl-notif-unread' }}">
                    <i class="bi {{ $nicon }} fs-5 flex-shrink-0 mt-1"></i>
                    <span class="flex-grow-1">
                        <span class="d-block small">{{ $ndata['pesan'] ?? 'Notifikasi baru' }}</span>
                        <span class="d-block text-secondary" style="font-size:.72rem;">{{ $notif->created_at->diffForHumans() }}</span>
                    </span>
                </a>
            @empty
                <div class="text-center text-secondary small py-4">
                    <i class="bi bi-bell-slash fs-4 d-block mb-1"></i>
                    Belum ada notifikasi.
                </div>
            @endforelse
        </div>
        <div class="text-center border-top py-2">
            <a href="{{ route('notifications.index') }}" class="small text-decoration-none fw-semibold">Lihat semua notifikasi</a>
        </div>
    </div>
</div>
