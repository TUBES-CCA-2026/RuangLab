@extends('layouts.admin')

@section('title', 'Tambah Laboratorium')
@section('page-title', 'Tambah Laboratorium')

@section('content')

<div class="card table-card">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.laboratorium.store') }}" enctype="multipart/form-data">
            @include('admin.laboratorium._form')
        </form>
    </div>
</div>

@endsection
