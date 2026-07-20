<?php
    $jamBukaOperasional  = '08:00';
    $jamTutupOperasional = '18:10';
    $createRouteName     = $createRoute ?? 'reservasi.create';

    $labsAktif   = \App\Models\MstLaboratorium::where('status', true)->orderBy('nama_lab')->get();
    $semuaJadwal = \App\Models\TrxJadwalKuliah::with(['laboratorium', 'hari'])->tahunAjaranAktif()->get();
    $hariIniIndex = \Carbon\Carbon::today()->dayOfWeekIso - 1;

    // Pecah satu rentang [mulai, selesai] jadi beberapa segmen kalau sebagian
    // waktunya ketiban reservasi yang sedang berlangsung (sudah check-in).
    $pecahSlotSedangDipakai = function (string $mulaiGap, string $selesaiGap, array $sedangDipakai) {
        $segmen = [];
        $kursor = $mulaiGap;

        foreach ($sedangDipakai as $d) {
            $mulaiOccupied   = max($d['mulai'], $mulaiGap);
            $selesaiOccupied = min($d['selesai'], $selesaiGap);

            if ($selesaiOccupied <= $kursor || $mulaiOccupied >= $selesaiGap) {
                continue; // tidak beririsan dengan gap ini
            }

            if ($mulaiOccupied > $kursor) {
                $segmen[] = ['mulai' => $kursor, 'selesai' => $mulaiOccupied, 'status' => 'kosong'];
            }
            $segmen[] = ['mulai' => max($mulaiOccupied, $kursor), 'selesai' => $selesaiOccupied, 'status' => 'dipakai'];
            $kursor = max($kursor, $selesaiOccupied);
        }

        if ($kursor < $selesaiGap) {
            $segmen[] = ['mulai' => $kursor, 'selesai' => $selesaiGap, 'status' => 'kosong'];
        }

        return $segmen;
    };

    $labKosongPerHari = collect(\App\Models\MstDay::URUTAN)->mapWithKeys(function ($namaHari, $indexHari) use ($labsAktif, $semuaJadwal, $jamBukaOperasional, $jamTutupOperasional, $hariIniIndex, $pecahSlotSedangDipakai) {
        $selisihHari   = ($indexHari - $hariIniIndex + 7) % 7;
        $tanggalTarget = \Carbon\Carbon::today()->addDays($selisihHari)->toDateString();

        // Reservasi yang sudah check-in (sedang dipakai) untuk tanggal konkret hari ini.
        $sedangDipakaiHariIni = \App\Models\TrxDetailReservasi::where('tanggal_pakai', $tanggalTarget)
            ->whereHas('reservasi', fn ($q) => $q->where('status', 'sedang_dipakai'))
            ->get();

        $perLab = $labsAktif->map(function ($lab) use ($namaHari, $semuaJadwal, $jamBukaOperasional, $jamTutupOperasional, $sedangDipakaiHariIni, $pecahSlotSedangDipakai) {
            $jadwalHariIni = $semuaJadwal
                ->filter(fn ($j) => ($j->laboratorium->id ?? null) === $lab->id && ($j->hari->nama_hari ?? null) === $namaHari)
                ->sortBy('jam_mulai')
                ->values();

            $slotKosong = [];
            $kursor = $jamBukaOperasional;

            foreach ($jadwalHariIni as $j) {
                $mulai   = \Illuminate\Support\Str::substr($j->jam_mulai, 0, 5);
                $selesai = \Illuminate\Support\Str::substr($j->jam_selesai, 0, 5);

                if ($mulai > $kursor) {
                    $slotKosong[] = ['mulai' => $kursor, 'selesai' => $mulai];
                }
                if ($selesai > $kursor) {
                    $kursor = $selesai;
                }
            }

            if ($kursor < $jamTutupOperasional) {
                $slotKosong[] = ['mulai' => $kursor, 'selesai' => $jamTutupOperasional];
            }

            $sedangDipakaiLab = $sedangDipakaiHariIni
                ->filter(fn ($d) => $d->id_ruangan === $lab->id)
                ->map(fn ($d) => ['mulai' => \Illuminate\Support\Str::substr($d->jam_mulai, 0, 5), 'selesai' => \Illuminate\Support\Str::substr($d->jam_selesai, 0, 5)])
                ->sortBy('mulai')
                ->values()
                ->all();

            $slotAkhir = [];
            foreach ($slotKosong as $gap) {
                array_push($slotAkhir, ...$pecahSlotSedangDipakai($gap['mulai'], $gap['selesai'], $sedangDipakaiLab));
            }

            return ['lab' => $lab, 'slot' => $slotAkhir];
        });

        return [$namaHari => ['tanggal' => $tanggalTarget, 'labs' => $perLab]];
    });
?>
<div class="card table-card mb-4">
    <div class="card-body p-4">
        <h6 class="fw-semibold mb-1">
            <i class="bi bi-calendar2-check me-1 text-success"></i>
            Lab Kosong (Sepekan)
            <?php if($tahunAjaranAktifNama = \App\Models\MstTahunAjaran::aktif()?->nama): ?>
                <span class="text-secondary fw-normal small ms-1">— <?php echo e($tahunAjaranAktifNama); ?></span>
            <?php endif; ?>
        </h6>
        <p class="text-secondary small mb-3">
            Slot kosong di luar jadwal praktikum tetap (<?php echo e($jamBukaOperasional); ?>–<?php echo e($jamTutupOperasional); ?>). Klik salah satu jam untuk langsung reservasi di tanggal terdekat sesuai hari itu. Jam yang sudah ada reservasi aktif (sudah check-in) ditandai "Sedang Dipakai". Reservasi yang belum check-in tidak ikut terhitung di sini — cek ulang saat mengajukan.
        </p>

        <?php if($labsAktif->isEmpty()): ?>
            <p class="text-secondary small mb-0">Belum ada laboratorium aktif.</p>
        <?php else: ?>
            <div class="row g-3">
                <?php $__currentLoopData = $labKosongPerHari; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hari => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 col-xl-4">
                    <div class="border rounded-3 overflow-hidden h-100">
                        <div class="p-2 bg-success-subtle border-start border-success border-3">
                            <p class="fw-semibold mb-0 small">
                                <?php echo e($hari); ?>

                                <span class="text-secondary fw-normal">— <?php echo e(\Carbon\Carbon::parse($data['tanggal'])->translatedFormat('d M')); ?></span>
                            </p>
                        </div>
                        <div class="p-2">
                            <?php $__currentLoopData = $data['labs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="mb-2" style="font-size:.78rem;">
                                <div class="fw-semibold"><i class="bi bi-building me-1"></i><?php echo e($item['lab']->nama_lab); ?></div>
                                <?php if(empty($item['slot'])): ?>
                                    <div class="text-secondary ps-3">Penuh seharian</div>
                                <?php else: ?>
                                    <div class="d-flex flex-wrap gap-1 ps-3 mt-1">
                                        <?php $__currentLoopData = $item['slot']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($slot['status'] === 'kosong'): ?>
                                            <a href="<?php echo e(route($createRouteName, ['lab' => $item['lab']->id, 'tanggal_pakai' => $data['tanggal'], 'jam_mulai' => $slot['mulai'], 'jam_selesai' => $slot['selesai']])); ?>"
                                               class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 text-decoration-none"
                                               style="font-size:.72rem;" title="Klik untuk reservasi jam ini">
                                                <?php echo e($slot['mulai']); ?>–<?php echo e($slot['selesai']); ?>

                                            </a>
                                            <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1"
                                                  style="font-size:.72rem;" title="Sedang dipakai — sudah check-in">
                                                <i class="bi bi-door-open me-1"></i><?php echo e($slot['mulai']); ?>–<?php echo e($slot['selesai']); ?> · Sedang Dipakai
                                            </span>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\RuangLab\resources\views/partials/lab-kosong-mingguan.blade.php ENDPATH**/ ?>