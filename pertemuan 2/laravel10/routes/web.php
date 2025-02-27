<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PhotoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/hello', function () {
    return 'Hello World';
});
Route::get('/World', function () {
    return 'World';
});
Route::get('/about', function () {
    return '2341720142';
});
Route::get('/Wellcome', function () {
    return 'Selamat Datang';
});
Route::get('/user/{yan}', function ($yan) {
    return 'Nama saya ' . $yan;
});
Route::get('/posts/{post}/comments/{comment}', function ($postId, $commentId) {
    return 'Pos ke-' . $postId . " Komentar ke-: " . $commentId;
});
Route::get('/articles/{id}', function ($id) {
    return 'Halaman Artikel Dengan ID' . $id;
});
Route::get('/user/{name?}', function ($name='yan') { return 'Nama saya '.$name;
});

Route::get('/hello', [WelcomeController::class, 'hello']);
Route::resource('photos', PhotoController::class);
Route::get('/greeting', [WelcomeController::class, 'greeting']);
