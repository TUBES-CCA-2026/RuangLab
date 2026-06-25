@extends('layouts.admin')

@section('title', 'Edit Reservasi')
@section('page-title', 'Edit Reservasi')

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
                <h6 class="fw-bold mb-1">Edit Reservasi</h6>
                <p class="small text-secondary mb-4">Pemohon: <strong>{{ $reservasi->user->nama ?? '-' }}</strong></p>

                @php $detail = $reservasi->detail->first(); @endphp

                <form method="POST" action="{{ route('admin.reservasi.update', $reservasi->id) }}">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Laboratorium</label>
                        <select name="id_ruangan" class="form-select" required>
                            @foreach($labs as $lab)
                                <option value="{{ $lab->id }}" {{ old('id_ruangan', $detail?->id_ruangan) == $lab->id ? 'selected' : '' }}>
                                    {{ $lab->nama_lab }}
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
                        <input type="date" name="tanggal_pakai" value="{{ old('tanggal_pakai', $detail?->tanggal_pakai) }}" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-semibold">Jam Mulai</label>
                            <input type="time" name="jam_mulai" value="{{ old('jam_mulai', Str::substr($detail?->jam_mulai, 0, 5)) }}" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-semibold">Jam Selesai <span class="text-secondary">(maks. 18:10)</span></label>
                            <input type="time" name="jam_selesai" value="{{ old('jam_selesai', Str::substr($detail?->jam_selesai, 0, 5)) }}" class="form-control" max="18:10" required>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Simpan</button>
                        <a href="{{ route('admin.reservasi.show', $reservasi->id) }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
