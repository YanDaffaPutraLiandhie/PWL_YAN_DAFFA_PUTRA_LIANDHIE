<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/level', [LevelController::class, 'index']);
Route::get('/kategori', [KategoriController::class, 'index']);
Route::get('/user', [UserController::class, 'index']);
Route::get('/user/tambah', [UserController::class,'Tambah']);
Route::post('/user/tambah_simpan', [UserController::class,'Tambah_simpan']);
Route::get('/user/ubah/{is}', [UserController::class,'ubah']);
Route::get('/user/ubah/{is}', [UserController::class,'ubah']);
Route::put('/user/ubah_simpan/{is}', [UserController::class,'ubah_simpan']);
Route::get('/user/ubah_hapus/{is}', [UserController::class,'hapus']);
