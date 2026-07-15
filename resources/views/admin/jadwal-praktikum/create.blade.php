@extends('layouts.admin')

@section('title', 'Tambah Jadwal Praktikum')
@section('page-title', 'Tambah Jadwal Praktikum')

@section('content')

<div class="card table-card">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.jadwal-praktikum.store') }}">
            @include('admin.jadwal-praktikum._form')
        </form>
    </div>
</div>

@endsection
