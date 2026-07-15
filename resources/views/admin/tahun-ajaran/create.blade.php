@extends('layouts.admin')

@section('title', 'Tambah Tahun Ajaran')
@section('page-title', 'Tambah Tahun Ajaran')

@section('content')

<div class="card table-card">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.tahun-ajaran.store') }}">
            @include('admin.tahun-ajaran._form')
        </form>
    </div>
</div>

@endsection
