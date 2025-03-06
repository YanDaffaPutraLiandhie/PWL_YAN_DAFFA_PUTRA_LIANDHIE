<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        // Tambah data user dengan Eloquent Model
        $data = [
            'nama' => 'Pelanggan Pertama'
        ];

        // Update data user berdasarkan username
        UserModel::where('username', 'customer-1')->update($data);

        // Ambil semua data dari tabel m_user
        $users = UserModel::all();

        // Return ke view dengan data
        return view('user', ['data' => $users]);
    }
}
