@php
    $jadwalMingguanGrouped = \App\Models\TrxJadwalKuliah::with(['mataKuliah', 'laboratorium', 'hari'])
        ->tahunAjaranAktif()
        ->get()
        ->groupBy(fn ($j) => $j->hari->nama_hari ?? '?')
        ->sortBy(fn ($items, $hari) => array_search($hari, \App\Models\MstDay::URUTAN));
@endphp
<div class="card table-card mb-4">
    <div class="card-body p-4">
        <h6 class="fw-semibold mb-1">
            <i class="bi bi-calendar-week me-1 text-primary"></i>
            Jadwal Praktikum Tetap (Sepekan)
            @if($tahunAjaranAktifNama = \App\Models\MstTahunAjaran::aktif()?->nama)
                <span class="text-secondary fw-normal small ms-1">— {{ $tahunAjaranAktifNama }}</span>
            @endif
        </h6>
        <p class="text-secondary small mb-3">Jadwal rutin mingguan untuk seluruh laboratorium di tahun ajaran yang sedang aktif. Lab otomatis terkunci pada jam ini — reservasi baru tidak bisa dibuat bentrok dengan jadwal ini.</p>

        @if($jadwalMingguanGrouped->isEmpty())
            <p class="text-secondary small mb-0">Belum ada jadwal praktikum tetap untuk tahun ajaran ini.</p>
        @else
            <div class="row g-3">
                @foreach($jadwalMingguanGrouped as $hari => $items)
                <div class="col-md-6 col-xl-4">
                    <div class="border rounded-3 overflow-hidden h-100">
                        <div class="p-2 bg-primary-subtle border-start border-primary border-3">
                            <p class="fw-semibold mb-0 small">{{ $hari }}</p>
                        </div>
                        <div class="p-2">
                            @foreach($items->sortBy('jam_mulai') as $jp)
                            <div class="border-start border-primary border-2 ps-2 mb-2 rounded" style="font-size:.78rem;">
                                <div class="fw-semibold">
                                    <i class="bi bi-clock me-1 text-primary"></i>
                                    {{ \Illuminate\Support\Str::substr($jp->jam_mulai,0,5) }} – {{ \Illuminate\Support\Str::substr($jp->jam_selesai,0,5) }}
                                </div>
                                <div>{{ $jp->mataKuliah->nama_matkul ?? '-' }}</div>
                                <div class="text-secondary">
                                    <i class="bi bi-building me-1"></i>{{ $jp->laboratorium->nama_lab ?? '-' }}
                                    · <i class="bi bi-person me-1"></i>{{ $jp->mataKuliah->nama_dosen ?? '-' }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
