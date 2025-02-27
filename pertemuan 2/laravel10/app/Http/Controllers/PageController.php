<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index() {
        return 'Selamat Datang';
    }
    
    public function about() {
        return 'Nama: Yan Daffa Putra Liandhie, NIM: 2341720142';
    }

    public function articles($id) {
        return 'Halaman Artikel dengan Id '. $id;
    }
}