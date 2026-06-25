@php
    $user = auth()->user();
    $layout = $user->isAdmin() ? 'layouts.admin' : ($user->isAslab() ? 'layouts.aslab' : 'layouts.app');
@endphp

@extends($layout)

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Semua Notifikasi</h6>
            @if(auth()->user()->unreadNotifications->count())
                <form method="POST" action="{{ route('notifications.markAllRead') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-check2-all me-1"></i> Tandai Semua Dibaca
                    </button>
                </form>
            @endif
        </div>

        @forelse($notifications as $notif)
        @php $data = $notif->data; @endphp
        <div class="card border-0 shadow-sm rounded-3 mb-3 {{ $notif->read_at ? 'opacity-75' : '' }}">
            <div class="card-body p-3 d-flex align-items-start gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0
                    {{ ($data['status'] ?? '') === 'disetujui' ? 'bg-success-subtle text-success' :
                       (($data['status'] ?? '') === 'ditolak' ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning') }}"
                     style="width:40px;height:40px;">
                    <i class="bi {{ ($data['status'] ?? '') === 'disetujui' ? 'bi-check-circle-fill' :
                                    (($data['status'] ?? '') === 'ditolak' ? 'bi-x-circle-fill' : 'bi-info-circle-fill') }}"></i>
                </div>
                <div class="flex-grow-1">
                    <p class="mb-1 fw-semibold small">{{ $data['pesan'] ?? 'Notifikasi baru' }}</p>
                    @if(!empty($data['catatan']))
                        <p class="mb-1 small text-secondary">Catatan: {{ $data['catatan'] }}</p>
                    @endif
                    <span class="text-secondary" style="font-size:.75rem;">
                        <i class="bi bi-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}
                        @if(!$notif->read_at)
                            <span class="badge bg-primary ms-2" style="font-size:.65rem;">Baru</span>
                        @endif
                    </span>
                </div>
                <div class="d-flex flex-column gap-1 align-items-end flex-shrink-0">
                    @if(!empty($data['reservasi_id']))
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.reservasi.show', $data['reservasi_id']) }}" class="btn btn-xs btn-outline-primary" style="font-size:.75rem;padding:2px 10px;">Lihat</a>
                        @elseif(auth()->user()->isAslab())
                            <a href="{{ route('aslab.reservasi.show', $data['reservasi_id']) }}" class="btn btn-xs btn-outline-primary" style="font-size:.75rem;padding:2px 10px;">Lihat</a>
                        @else
                            <a href="{{ route('reservasi.show', $data['reservasi_id']) }}" class="btn btn-xs btn-outline-primary" style="font-size:.75rem;padding:2px 10px;">Lihat</a>
                        @endif
                    @endif
                    <form method="POST" action="{{ route('notifications.destroy', $notif->id) }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-xs btn-outline-danger" style="font-size:.75rem;padding:2px 10px;">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="bi bi-bell-slash fs-1 text-secondary"></i>
            <p class="text-secondary mt-2">Belum ada notifikasi.</p>
        </div>
        @endforelse

        <div class="mt-3">{{ $notifications->links() }}</div>
    </div>
</div>
@endsection
