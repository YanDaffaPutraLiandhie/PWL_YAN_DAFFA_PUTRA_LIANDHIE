@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Halo, apakabar!!!</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            <p></p>

            <!-- Grid untuk kotak besar -->
            <div class="row">
                <!-- Kotak Kategori -->
                <div class="col-md-4 mb-3">
                    <a href="{{ url('/kategori') }}" class="card-link">
                        <div class="card h-100 bg-primary text-white">
                            <div class="card-body text-center">
                                <h5></h5>
                                <p class="card-text">Kategori Barang</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Kotak User -->
                <div class="col-md-4 mb-3">
                    <a href="{{ url('/user') }}" class="card-link">
                        <div class="card h-100 bg-success text-white">
                            <div class="card-body text-center">
                                <h5></h5>
                                <p class="card-text">User</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Kotak Stok -->
                <div class="col-md-4 mb-3">
                    <a href="{{ url('/stok') }}" class="card-link">
                        <div class="card h-100 bg-info text-white">
                            <div class="card-body text-center">
                                <p class="card-text">Stok</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Kotak Supplier -->
                <div class="col-md-4 mb-3">
                    <a href="{{ url('/supplier') }}" class="card-link">
                        <div class="card h-100 bg-warning text-dark">
                            <div class="card-body text-center">
                                <h5></h5>
                                <p class="card-text">Supplier</p>
                            </div>
                        </div>
                    </a>
                </div>


                <!-- Kotak Barang -->
                <div class="col-md-4 mb-3">
                    <a href="{{ url('/barang') }}" class="card-link">
                        <div class="card h-100 bg-danger text-white">
                            <div class="card-body text-center">
                                <h5></h5>
                                <p class="card-text">Barang</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Kotak Level -->
                <div class="col-md-4 mb-3">
                    <a href="{{ url('/level') }}" class="card-link">
                        <div class="card h-100 bg-secondary text-white">
                            <div class="card-body text-center">
                                <h5></h5>
                                <p class="card-text">Level</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Kotak Penjualan (diletakkan di tengah) -->
                <div class="col-md-4 offset-md-4 mb-3">
                    <a href="{{ url('/penjualan') }}" class="card-link">
                        <div class="card h-100 bg-dark text-white">
                            <div class="card-body text-center">
                                <h5></h5>
                                <p class="card-text">Penjualan</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card-link {
            text-decoration: none;
            /* Menghapus underline pada link */
            color: inherit;
            /* Menjaga warna teks sesuai dengan tema */
        }

        .card-link .card {
            cursor: pointer;
            /* Menambahkan pointer saat hover pada card */
            transition: transform 0.3s ease;
            /* Efek transisi pada hover */
        }

        .card-link .card:hover {
            transform: scale(1.05);
            /* Efek zoom saat hover */
        }

        .card-body {
            padding: 20px;
        }
    </style>
@endpush