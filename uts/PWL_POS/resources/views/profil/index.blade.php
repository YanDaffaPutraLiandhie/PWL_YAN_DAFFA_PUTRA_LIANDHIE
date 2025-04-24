@extends('layouts.template')

@section('content')

{{-- Tambahkan Font Awesome untuk ikon mata --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<h1>Profil Saya</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <h4>Oops! Ada kesalahan saat menyimpan data:</h4>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Breadcrumb Header --}}
<section class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>{{ $breadcrumb['title'] }}</h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            @foreach($breadcrumb['list'] as $idx => $item)
            @if($idx === count($breadcrumb['list']) - 1)
                <li class="breadcrumb-item active">{{ $item['label'] }}</li>
            @else
                <li class="breadcrumb-item">
                <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                </li>
            @endif
            @endforeach
        </ol>
        </div>
    </div>
    </div>
</section>

<form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="card mb-4">
    <div class="card-body">
        <div class="row">
        <div class="col-md-4 mb-3">
            <label for="nama">Nama</label>
            <input type="text" 
                class="form-control" 
                id="nama" 
                name="nama" 
                value="{{ old('nama', $user->nama) }}">
        </div>

        <div class="col-md-4 mb-3">
            <label for="username">Username</label>
            <input type="text" 
                class="form-control" 
                id="username" 
                name="username" 
                value="{{ old('username', $user->username) }}">
        </div>

        <div class="col-md-4 mb-3">
            <label for="email">Email</label>
            <input type="email" 
                class="form-control" 
                id="email" 
                name="email" 
                value="{{ old('email', $user->email) }}">
        </div>

        <div class="col-md-4 mb-3">
            <label for="no_telp">No. Telepon</label>
            <input type="text" 
                class="form-control" 
                id="no_telp" 
                name="no_telp" 
                value="{{ old('no_telp', $user->no_telp) }}">
        </div>

        <div class="col-md-8 mb-3">
            <label for="alamat">Alamat</label>
            <textarea class="form-control" 
                    id="alamat" 
                    name="alamat" 
                    rows="3">{{ old('alamat', $user->alamat) }}</textarea>
        </div>

        <div class="col-md-4 mb-3">
            <label for="foto">Foto Profil</label><br>
            @if($user->foto)
            <img src="{{ asset('storage/' . $user->foto) }}" 
                alt="Foto Profil" 
                class="img-thumbnail mb-2" 
                width="100">
            @endif
            <input type="file" 
                class="form-control" 
                id="foto" 
                name="foto">
        </div>
        </div>
    </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Ganti Password (Opsional)</h5>
        </div>
        <div class="card-body">
            <div class="form-group mb-3">
              <label for="password_lama">Password Lama</label>
              <div class="input-group">
                <input type="password" class="form-control" id="password_lama" name="password_lama">
                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_lama', 'icon_password_lama')">
                  <i class="fa fa-eye" id="icon_password_lama"></i>
                </button>
              </div>
            </div>
      
            <div class="form-group mb-3">
              <label for="password">Password Baru</label>
              <div class="input-group">
                <input type="password" class="form-control" id="password" name="password">
                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password', 'icon_password')">
                  <i class="fa fa-eye" id="icon_password"></i>
                </button>
              </div>
            </div>
      
            <div class="form-group mb-3">
              <label for="password_confirmation">Konfirmasi Password Baru</label>
              <div class="input-group">
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation', 'icon_password_confirmation')">
                  <i class="fa fa-eye" id="icon_password_confirmation"></i>
                </button>
              </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
</form>

{{-- Script toggle password --}}
<script>
  function togglePassword(idInput, idIcon) {
    const input = document.getElementById(idInput);
    const icon = document.getElementById(idIcon);
    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
    } else {
      input.type = 'password';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    }
  }
</script>

@endsection
