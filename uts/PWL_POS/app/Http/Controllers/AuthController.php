<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {    // jika sudah login, maka redirect ke halaman home
            return redirect('/');
        }
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $credentials = $request->only('username', 'password');

            if (Auth::attempt($credentials)) {
                return response()->json([
                    'status'    => true,
                    'message'   => 'Login Berhasil',
                    'redirect'  => url('/')
                ]);
            }
            return response()->json([
                'status'  => false,
                'message' => 'Login Gagal'
            ]);
        }
        return redirect('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }

    // =========== Jobsheet 7 - Tugas No. 4 ============
    public function register() {
        if (Auth::check()) {        // dilakukannya pengecekkan
            return redirect('/');   // jika sudah melakukan login akan diarahkan ke dashboard
        }

        // mengambil data level dari tabel m_level
        $levels = LevelModel::all();

        // menampilkan page registrasi dan data level
        return view('auth.register', compact('levels'));
    }

    public function postRegister(Request $request) {
        //terdapat aturan untuk input register
        $rules = [
            'username'  => 'required|string|min:3|unique:m_user,username',
            'nama'      => 'required|string|max:100',
            'password'  => 'required|min:5|confirmed', // perlu adanya konfirmasi pada form
            'level_id'  => 'required|exists:m_level,level_id', // pastikan level valid
        ];

        // melakukan validasi
        $validator = Validator::make($request->all(), $rules);

        // bila validasi gagal, kirim response error
        if ($validator->fails()) {
            return response()->json([
                'status'   => false,
                'message'  => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        // menyimpan user baru ke database
        UserModel::create([
            'username' => $request->username,
            'nama'     => $request->nama,
            'password' => $request->password,       //tidak perlu di hash, karena sudah otomatis di-hash oleh casts di model
            'level_id' => $request->level_id
        ]);

        // terdapat response berhasil 
        return response()->json([
            'status'  => true,
            'message' => 'Registrasi berhasil. Silakan login untuk melanjutkan!',
            'redirect' => url('login')
        ]);
    }
}
