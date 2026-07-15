@extends('layouts.admin')

@section('title', 'Edit Tahun Ajaran')
@section('page-title', 'Edit Tahun Ajaran')

@section('content')

<div class="card table-card">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.tahun-ajaran.update', $tahunAjaran->id) }}">
            @include('admin.tahun-ajaran._form')
        </form>
    </div>
</div>

@endsection
