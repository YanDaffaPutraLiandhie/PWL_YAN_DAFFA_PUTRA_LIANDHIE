@extends('layout.app')

@section('subtitles', 'kategori')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'kategori')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="card-body">
                    {{$dataTable->table()}}
                    <a href="{{ url('/kategori/create') }}" class="btn btn-primary btn-md ms-auto">Tambah Kategori</a>
                </div>
            </div>
        </div>
    </div>
    
@endsection
@push('scripts')
    {{$dataTable->scripts()}}
@endpush