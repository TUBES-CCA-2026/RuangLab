@extends('layouts.admin')

@section('title', 'Tambah Mata Kuliah')
@section('page-title', 'Tambah Mata Kuliah')

@section('content')

<div class="card table-card">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.mata-kuliah.store') }}">
            @include('admin.mata-kuliah._form')
        </form>
    </div>
</div>

@endsection
