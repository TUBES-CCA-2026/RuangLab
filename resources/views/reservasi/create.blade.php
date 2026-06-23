@extends('layouts.app')

@section('title', 'Ajukan Reservasi')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="mb-4">
                    <h2 class="fw-bold mb-1">Ajukan Reservasi Laboratorium</h2>
                    <p class="text-secondary">Lengkapi formulir berikut untuk mengajukan penggunaan laboratorium</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger rounded-3">
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card border-0 rounded-xl shadow-sm">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('reservasi.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Pilih Laboratorium</label>
                                <select name="id_ruangan" class="form-select" required>
                                    <option value="">-- Pilih Laboratorium --</option>
                                    @foreach($labs as $lab)
                                        <option value="{{ $lab->id }}" {{ old('id_ruangan', $labTerpilih) == $lab->id ? 'selected' : '' }}>
                                            {{ $lab->nama_lab }} (Kapasitas {{ $lab->kapasitas }} orang)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Keperluan</label>
                                <input type="text" name="keperluan" value="{{ old('keperluan') }}" class="form-control" placeholder="Contoh: Praktikum Basis Data" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Tanggal Pemakaian</label>
                                <input type="date" name="tanggal_pakai" value="{{ old('tanggal_pakai') }}" class="form-control" min="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label small fw-semibold">Jam Mulai</label>
                                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai') }}" class="form-control" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label small fw-semibold">Jam Selesai</label>
                                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai') }}" class="form-control" required>
                                </div>
                            </div>

                            <div class="alert alert-info small rounded-3">
                                <i class="bi bi-info-circle"></i> Pengajuan akan diperiksa oleh admin. Kamu akan mendapatkan kode check-in setelah reservasi disetujui.
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="bi bi-send-check"></i> Kirim Pengajuan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
