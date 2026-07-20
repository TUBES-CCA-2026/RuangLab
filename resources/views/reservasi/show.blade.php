@extends('layouts.app')

@section('title', 'Detail Reservasi')

@section('content')
<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('reservasi.index') }}">Reservasi Saya</a></li>
                <li class="breadcrumb-item active">Detail Reservasi</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card border-0 rounded-xl shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h4 class="fw-bold mb-1">Detail Reservasi</h4>
                                <p class="text-secondary small mb-0">Diajukan {{ \Carbon\Carbon::parse($reservasi->tanggal_pengajuan)->translatedFormat('d M Y') }}</p>
                            </div>
                            <span class="badge rounded-pill badge-status-{{ $reservasi->status }} text-white px-3 py-2">
                                {{ ucwords(str_replace('_', ' ', $reservasi->status)) }}
                            </span>
                        </div>

                        @if($reservasi->trashed())
                        <div class="alert alert-secondary rounded-3 mb-3">
                            <i class="bi bi-trash me-1"></i> Reservasi ini sudah dibatalkan/dihapus. Data ditampilkan sebagai arsip.
                        </div>
                        @endif

                        <hr>

                        @foreach($reservasi->detail as $d)
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-2"><i class="bi bi-building"></i> {{ $d->laboratorium->nama_lab ?? '-' }}</h6>
                            <ul class="list-unstyled small text-secondary mb-0">
                                <li class="mb-1"><i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::parse($d->tanggal_pakai)->translatedFormat('d F Y') }}</li>
                                <li><i class="bi bi-clock me-1"></i> {{ \Illuminate\Support\Str::substr($d->jam_mulai,0,5) }} - {{ \Illuminate\Support\Str::substr($d->jam_selesai,0,5) }}</li>
                                @if($d->mataKuliah)
                                <li><i class="bi bi-journal-bookmark me-1"></i> {{ $d->mataKuliah->nama_matkul }}</li>
                                @endif
                            </ul>
                        </div>
                        @endforeach

                        <hr>

                        <p class="mb-1"><span class="fw-semibold">Keperluan:</span> {{ $reservasi->keperluan }}</p>

                        @if(!$reservasi->trashed() && $reservasi->status === 'disetujui')
                        <div class="alert alert-success rounded-3 mt-3">
                            <i class="bi bi-check-circle"></i> Reservasi disetujui! Saat tiba di lokasi, scan QR yang ditunjukkan laboran untuk check-in.
                        </div>

                        {{-- ===== SCAN SECTION (PEMINJAM) ===== --}}
                        <div class="text-center mt-3 mb-2">
                            <div class="card border-0 bg-light rounded-3 p-4">
                                <p class="small text-secondary mb-3 fw-semibold">
                                    <i class="bi bi-qr-code-scan me-1"></i> Check-in via QR
                                </p>

                                <button type="button" id="btn-open-scan" class="btn btn-primary">
                                    <i class="bi bi-camera me-1"></i> Scan QR Check-in
                                </button>

                               {{-- Area kamera (disembunyikan sampai tombol ditekan) --}}
                                <div id="scan-wrap" class="mt-3" style="display:none;">
                                    <div id="qr-reader" class="mx-auto" style="max-width:320px;"></div>
                                    <div id="scan-status" class="small mt-2 text-secondary"></div>
                                    <button type="button" id="btn-close-scan" class="btn btn-sm btn-outline-secondary mt-2">
                                        Tutup kamera
                                    </button>
                                </div>

                                {{-- Input manual kalau kamera gagal --}}
                                <div id="manual-wrap" class="mt-3" style="display:none;">
                                    <p class="small text-secondary">Atau masukkan kode check-in secara manual:</p>
                                    <div class="input-group">
                                        <input type="text" id="input-kode" class="form-control"
                                               placeholder="Contoh: CHK-ABC123" style="text-transform:uppercase;">
                                        <button class="btn btn-outline-primary" id="btn-manual-checkin">
                                            <i class="bi bi-check-lg"></i> Check-in
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- ===== END SCAN SECTION ===== --}}

                        @elseif($reservasi->status === 'sedang_dipakai')
                        <div class="alert alert-info rounded-3 mt-3">
                            <i class="bi bi-check-circle"></i> Sudah check-in
                            @if(!empty($reservasi->checked_in_at))
                                pada {{ \Carbon\Carbon::parse($reservasi->checked_in_at)->translatedFormat('d M Y H:i') }}
                            @endif. Ruangan sedang dipakai.
                        </div>
                        @elseif($reservasi->status === 'ditolak')
                        <div class="alert alert-danger rounded-3 mt-3">
                            <i class="bi bi-x-circle"></i> Reservasi ditolak.
                        </div>
                        @elseif($reservasi->status === 'pending')
                        <div class="alert alert-warning rounded-3 mt-3">
                            <i class="bi bi-hourglass-split"></i> Menunggu persetujuan admin.
                        </div>
                        @endif

                        @if($reservasi->catatan_admin)
                        <div class="mt-3">
                            <span class="fw-semibold small">Catatan Admin:</span>
                            <p class="text-secondary small mb-0">{{ $reservasi->catatan_admin }}</p>
                        </div>
                        @endif

                        @if(!$reservasi->trashed() && in_array($reservasi->status, ['pending', 'disetujui']))
                        <hr>
                        <div class="d-flex gap-2">
                            <a href="{{ route('reservasi.edit', $reservasi->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('reservasi.destroy', $reservasi->id) }}"
                                  data-confirm="Batalkan reservasi ini?">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash me-1"></i> Batalkan
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if($reservasi->status === 'disetujui')
<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
(function () {
    const openBtn  = document.getElementById('btn-open-scan');
    const closeBtn = document.getElementById('btn-close-scan');
    const wrap     = document.getElementById('scan-wrap');
    const statusEl = document.getElementById('scan-status');
    let   scanner  = null;
    let   done     = false;

    function setStatus(msg, cls) {
        statusEl.textContent = msg;
        statusEl.className = 'small mt-2 ' + (cls || 'text-secondary');
    }

    function onScanSuccess(decodedText) {
        if (done) return;
        let sameOrigin = false;
        try {
            const u = new URL(decodedText, window.location.origin);
            sameOrigin = (u.origin === window.location.origin);
        } catch (e) { sameOrigin = false; }

        if (!sameOrigin) {
            setStatus('QR tidak dikenali. Pastikan ini QR dari RuangLab.', 'text-danger');
            return;
        }
        done = true;
        setStatus('QR terbaca, memproses check-in...', 'text-success');
        const go = function () { window.location.href = decodedText; };
        if (scanner) { scanner.stop().then(go).catch(go); } else { go(); }
    }

    function startScan() {
        wrap.style.display = 'block';
        openBtn.style.display = 'none';
        setStatus('Mengaktifkan kamera...');

        scanner = new Html5Qrcode('qr-reader');
        scanner.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: 240 },
            onScanSuccess
        ).then(function () {
            setStatus('Arahkan kamera ke QR di layar laboran.');
        }).catch(function (err) {
            setStatus('Gagal akses kamera. Gunakan input manual di bawah.', 'text-danger');
            document.getElementById('manual-wrap').style.display = 'block';
        });
    }

    function stopScan() {
        const reset = function () {
            wrap.style.display = 'none';
            openBtn.style.display = 'inline-block';
            setStatus('');
            done = false;
            scanner = null;
        };
        if (scanner) { scanner.stop().then(reset).catch(reset); } else { reset(); }
    }

    openBtn.addEventListener('click', startScan);
    closeBtn.addEventListener('click', stopScan);

    document.getElementById('btn-manual-checkin').addEventListener('click', function () {
        const kode = document.getElementById('input-kode').value.trim();
        if (!kode) { alert('Masukkan kode check-in terlebih dahulu.'); return; }
        window.location.href = '/reservasi/checkin/' + kode;
    });
})();
</script>
@endif

@endsection
