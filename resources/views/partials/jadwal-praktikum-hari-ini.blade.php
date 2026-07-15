@php
    $namaHariIni = \App\Models\MstDay::namaHariIni();
    $jadwalPraktikumHariIni = \App\Models\TrxJadwalKuliah::with(['mataKuliah', 'laboratorium'])
        ->whereHas('hari', fn ($q) => $q->where('nama_hari', $namaHariIni))
        ->orderBy('jam_mulai')
        ->get();
@endphp
<div class="card table-card mb-4">
    <div class="card-body p-4">
        <h6 class="fw-semibold mb-3">
            <i class="bi bi-calendar2-week me-1 text-primary"></i>
            Jadwal Praktikum Hari Ini
            <span class="text-secondary fw-normal small ms-1">— {{ $namaHariIni }}, {{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}</span>
        </h6>

        @if($jadwalPraktikumHariIni->isEmpty())
            <p class="text-secondary small mb-0">Tidak ada jadwal praktikum hari ini.</p>
        @else
            <div class="d-flex flex-column gap-2">
                @foreach($jadwalPraktikumHariIni as $jp)
                <div class="d-flex align-items-start gap-2 p-2 rounded-3 bg-light">
                    <div class="text-primary fw-semibold text-nowrap" style="font-size:.8rem;min-width:95px;">
                        {{ \Illuminate\Support\Str::substr($jp->jam_mulai,0,5) }} – {{ \Illuminate\Support\Str::substr($jp->jam_selesai,0,5) }}
                    </div>
                    <div>
                        <div class="fw-semibold" style="font-size:.82rem;">{{ $jp->mataKuliah->nama_matkul ?? '-' }}</div>
                        <div class="text-secondary" style="font-size:.75rem;">
                            <i class="bi bi-building me-1"></i>{{ $jp->laboratorium->nama_lab ?? '-' }}
                            · <i class="bi bi-person me-1"></i>{{ $jp->mataKuliah->nama_dosen ?? '-' }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
