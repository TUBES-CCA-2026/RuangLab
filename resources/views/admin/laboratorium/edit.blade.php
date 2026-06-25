@extends('layouts.admin')

@section('title', 'Edit Laboratorium')
@section('page-title', 'Edit Laboratorium')

@section('content')

<div class="card table-card">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.laboratorium.update', $lab->id) }}" enctype="multipart/form-data">
            @include('admin.laboratorium._form')
        </form>
    </div>
</div>

@endsection
