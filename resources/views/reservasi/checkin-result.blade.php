@extends('layouts.app')

@section('title', 'Hasil Check-in')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card border-0 rounded-xl shadow-sm">
                    <div class="card-body p-5 text-center">

                        @if($ok)
                            <div class="mb-3" style="font-size:48px;line-height:1;color:#15b27a;">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <h4 class="fw-bold mb-2">Berhasil</h4>
                        @else
                            <div class="mb-3" style="font-size:48px;line-height:1;color:#e2483d;">
                                <i class="bi bi-x-circle-fill"></i>
                            </div>
                            <h4 class="fw-bold mb-2">Gagal</h4>
                        @endif

                        <p class="text-secondary mb-3">{{ $message }}</p>

                        @isset($reservasi)
                        <div class="bg-light rounded-3 p-3 small text-secondary mb-3">
                            <div class="fw-semibold text-dark">{{ $reservasi->kode_reservasi }}</div>
                            @if(!empty($reservasi->checked_in_at))
                                <div><i class="bi bi-clock-history me-1"></i>
                                    Check-in {{ \Carbon\Carbon::parse($reservasi->checked_in_at)->translatedFormat('d M Y H:i') }}
                                </div>
                            @endif
                        </div>
                        @endisset

                        <a href="{{ route('reservasi.index') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Reservasi Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
