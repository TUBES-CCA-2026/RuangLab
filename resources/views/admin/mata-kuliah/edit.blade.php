@extends('layouts.admin')

@section('title', 'Edit Mata Kuliah')
@section('page-title', 'Edit Mata Kuliah')

@section('content')

<div class="card table-card">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.mata-kuliah.update', $matkul->id) }}">
            @include('admin.mata-kuliah._form')
        </form>
    </div>
</div>

@endsection
