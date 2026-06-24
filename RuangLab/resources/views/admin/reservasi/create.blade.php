@extends('layouts.admin')

@section('title', 'Buat Reservasi')
@section('page-title', 'Buat Reservasi')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">

        @if($errors->any())
            <div class="alert alert-danger rounded-3">
                <ul class="mb-0 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card table-card">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-1">Buat Reservasi Laboratorium</h6>
                <p class="small text-secondary mb-4">Reservasi yang dibuat laboran langsung berstatus <strong>Disetujui</strong>.</p>

                <form method="POST" action="{{ route('admin.reservasi.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Laboratorium</label>
                        <select name="id_ruangan" class="form-select" required>
                            <option value="">-- Pilih Laboratorium --</option>
                            @foreach($labs as $lab)
                                <option value="{{ $lab->id }}" {{ old('id_ruangan') == $lab->id ? 'selected' : '' }}>
                                    {{ $lab->nama_lab }} (Kapasitas {{ $lab->kapasitas }})
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
                            <label class="form-label small fw-semibold">Jam Selesai <span class="text-secondary">(maks. 18:10)</span></label>
                            <input type="time" name="jam_selesai" value="{{ old('jam_selesai') }}" class="form-control" max="18:10" required>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-calendar-check me-1"></i> Buat Reservasi
                        </button>
                        <a href="{{ route('admin.reservasi.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
