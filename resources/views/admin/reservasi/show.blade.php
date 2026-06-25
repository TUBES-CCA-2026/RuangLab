@extends('layouts.admin')

@section('title', 'Detail Reservasi')
@section('page-title', 'Detail Reservasi')

@section('content')

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card table-card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <h5 class="fw-bold mb-0">Detail Reservasi</h5>
                            @if($reservasi->is_prioritas)
                                <span class="badge rounded-pill text-white px-2 py-1"
                                      style="font-size:.7rem;background:linear-gradient(135deg,#f59e0b,#ef4444);">
                                    <i class="bi bi-star-fill me-1" style="font-size:.55rem;"></i>Prioritas Aslab
                                </span>
                            @endif
                        </div>
                        <p class="text-secondary small mb-0">Diajukan {{ \Carbon\Carbon::parse($reservasi->tanggal_pengajuan)->format('d M Y') }}</p>
                    </div>
                    <span class="badge rounded-pill badge-status-{{ $reservasi->status }} text-white px-3 py-2">
                        {{ ucwords(str_replace('_', ' ', $reservasi->status)) }}
                    </span>
                </div>

                <hr>

                <h6 class="fw-semibold mb-2">Pemohon</h6>
                <ul class="list-unstyled small text-secondary mb-4">
                    <li><i class="bi bi-person me-1"></i> {{ $reservasi->user->nama ?? '-' }}</li>
                    <li><i class="bi bi-envelope me-1"></i> {{ $reservasi->user->email ?? '-' }}</li>
                    <li><i class="bi bi-telephone me-1"></i> {{ $reservasi->user->no_telp ?? '-' }}</li>
                </ul>

                <h6 class="fw-semibold mb-2">Detail Pemakaian</h6>
                @foreach($reservasi->detail as $d)
                <div class="mb-3 p-3 bg-light rounded-3">
                    <p class="fw-semibold mb-1"><i class="bi bi-building"></i> {{ $d->laboratorium->nama_lab ?? '-' }}</p>
                    <p class="small text-secondary mb-0">
                        <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($d->tanggal_pakai)->translatedFormat('d F Y') }}
                        &nbsp;|&nbsp;
                        <i class="bi bi-clock"></i> {{ \Illuminate\Support\Str::substr($d->jam_mulai,0,5) }} - {{ \Illuminate\Support\Str::substr($d->jam_selesai,0,5) }}
                    </p>
                </div>
                @endforeach

                <p class="mb-0"><span class="fw-semibold">Keperluan:</span> {{ $reservasi->keperluan }}</p>

                @if($reservasi->status === 'disetujui' || $reservasi->status === 'sedang_dipakai')
                <hr>

                {{-- ===== QR SECTION (LABORAN/ADMIN) ===== --}}
                <h6 class="fw-semibold mb-3"><i class="bi bi-qr-code me-1"></i> QR Check-in</h6>
                <div class="text-center bg-light rounded-3 p-4">

                    @if($reservasi->status === 'sedang_dipakai')
                        <div class="alert alert-info rounded-3 mb-3 small mb-3">
                            <i class="bi bi-check-circle me-1"></i> Peminjam sudah check-in
                            @if(!empty($reservasi->checked_in_at))
                                pada {{ \Carbon\Carbon::parse($reservasi->checked_in_at)->translatedFormat('d M Y H:i') }}
                            @endif.
                        </div>
                    @else
                        <p class="small text-secondary mb-3 fw-semibold">
                            Minta peminjam scan QR ini untuk check-in
                        </p>
                    @endif

                    {{-- QR dirender oleh qrcode.js --}}
                    <div id="qrcode-admin" class="d-inline-block bg-white p-3 rounded-3"></div>

                    {{-- Kode teks di bawah QR (cadangan kalau scan gagal) --}}
                    <div class="mt-3 font-monospace fw-bold fs-5" style="letter-spacing:4px">
                        {{ $reservasi->kode_checkin }}
                    </div>

                    <button onclick="printQR()" class="btn btn-sm btn-outline-secondary mt-3">
                        <i class="bi bi-printer me-1"></i> Cetak QR
                    </button>
                </div>
                {{-- ===== END QR SECTION ===== --}}
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card table-card mb-4">
            <div class="card-body p-4">
                <h6 class="fw-semibold mb-3">Aksi Cepat</h6>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.reservasi.edit', $reservasi->id) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-pencil me-1"></i> Edit Reservasi
                    </a>
                    <form method="POST" action="{{ route('admin.reservasi.destroy', $reservasi->id) }}"
                          onsubmit="return confirm('Hapus reservasi ini permanen?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash me-1"></i> Hapus</button>
                    </form>
                    <a href="{{ route('admin.reservasi.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="card table-card">
            <div class="card-body p-4">
                <h6 class="fw-semibold mb-3">Update Status</h6>
                <form method="POST" action="{{ route('admin.reservasi.updateStatus', $reservasi->id) }}">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Status Baru</label>
                        <select name="status" class="form-select" required>
                            <option value="disetujui" {{ $reservasi->status == 'disetujui' ? 'selected' : '' }}>Setujui</option>
                            <option value="ditolak" {{ $reservasi->status == 'ditolak' ? 'selected' : '' }}>Tolak</option>
                            <option value="sedang_dipakai" {{ $reservasi->status == 'sedang_dipakai' ? 'selected' : '' }}>Sedang Dipakai</option>
                            <option value="hangus" {{ $reservasi->status == 'hangus' ? 'selected' : '' }}>Hangus</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Catatan Admin (opsional)</label>
                        <textarea name="catatan_admin" rows="3" class="form-control" placeholder="Catatan untuk pemohon...">{{ $reservasi->catatan_admin }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@if($reservasi->status === 'disetujui' || $reservasi->status === 'sedang_dipakai')
{{-- Load qrcode.js dari CDN --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    // URL yang disisipkan ke dalam QR. Saat peminjam scan, browser mereka
    // membuka URL ini (otomatis ke endpoint check-in).
    const checkinUrl = window.location.origin + '/reservasi/checkin/{{ $reservasi->kode_checkin }}';

    document.addEventListener('DOMContentLoaded', function () {
        new QRCode(document.getElementById('qrcode-admin'), {
            text: checkinUrl,
            width: 200,
            height: 200,
            colorDark: "#10172a",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.M
        });
    });

    function printQR() {
        const qrHtml = document.getElementById('qrcode-admin').innerHTML;
        const kode   = @json($reservasi->kode_checkin);
        const nama   = @json($reservasi->user->nama ?? '');
        const win    = window.open('', '_blank');

        win.document.write(`
            <html>
            <head>
                <title>Cetak QR Check-in</title>
                <style>
                    body { font-family: sans-serif; text-align: center; padding: 40px; }
                    h3   { margin-bottom: 4px; }
                    p    { color: #666; font-size: 13px; margin: 4px 0; }
                    .kode { font-size: 20px; font-weight: bold; font-family: monospace; margin-top: 12px; letter-spacing: 4px; }
                    img, canvas { max-width: 260px; }
                </style>
            </head>
            <body>
                <h3>QR Check-in Reservasi</h3>
                <p>${nama}</p>
                ${qrHtml}
                <div class="kode">${kode}</div>
                <script>window.print(); window.close();<\/script>
            </body>
            </html>
        `);
        win.document.close();
    }
</script>
@endif

@endsection
