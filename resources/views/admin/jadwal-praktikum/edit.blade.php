@extends('layouts.admin')

@section('title', 'Edit Jadwal Praktikum')
@section('page-title', 'Edit Jadwal Praktikum')

@section('content')

<div class="card table-card">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.jadwal-praktikum.update', $jadwal->id) }}">
            @method('PUT')
            @include('admin.jadwal-praktikum._form')
        </form>
    </div>
</div>

@endsection
