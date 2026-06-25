@extends('layouts.app')

@section('title', 'Edit Reservasi')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="mb-4">
                    <h2 class="fw-bold mb-1">Edit Reservasi</h2>
                    <p class="text-secondary">Perbarui detail reservasi laboratoriummu</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger rounded-3">
                        <ul class="mb-0 small">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @php $detail = $reservasi->detail->first(); @endphp

                <div class="card border-0 rounded-xl shadow-sm">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('reservasi.update', $reservasi->id) }}">
                            @csrf @method('PUT')

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Pilih Laboratorium</label>
                                <select name="id_ruangan" class="form-select" required>
                                    <option value="">-- Pilih Laboratorium --</option>
                                    @foreach($labs as $lab)
                                        <option value="{{ $lab->id }}" {{ old('id_ruangan', $detail?->id_ruangan) == $lab->id ? 'selected' : '' }}>
                                            {{ $lab->nama_lab }} (Kapasitas {{ $lab->kapasitas }} orang)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Keperluan</label>
                                <input type="text" name="keperluan" value="{{ old('keperluan', $reservasi->keperluan) }}" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Tanggal Pemakaian</label>
                                <input type="date" name="tanggal_pakai" value="{{ old('tanggal_pakai', $detail?->tanggal_pakai) }}" class="form-control" min="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label small fw-semibold">Jam Mulai</label>
                                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai', \Illuminate\Support\Str::substr($detail?->jam_mulai, 0, 5)) }}" class="form-control" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label small fw-semibold">Jam Selesai <span class="text-secondary">(maks. 18:10)</span></label>
                                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai', \Illuminate\Support\Str::substr($detail?->jam_selesai, 0, 5)) }}" class="form-control" max="18:10" required>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100 py-2">
                                    <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                                </button>
                                <a href="{{ route('reservasi.show', $reservasi->id) }}" class="btn btn-outline-secondary py-2">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
