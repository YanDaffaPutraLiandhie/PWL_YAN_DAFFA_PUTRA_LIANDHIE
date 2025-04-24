<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
    /**
     * Menampilkan halaman profil pengguna
     */
    public function index()
    {
        $user = Auth::user();

        // Path foto profil untuk diakses publik
        $fotoPath = asset('storage/foto_profil/user_' . $user->id . '.jpg');
        
        // Cek apakah file ada
        if (!file_exists(public_path('storage/foto_profil/user_' . $user->id . '.jpg'))) {
            $fotoPath = asset('storage/foto_profil/user_.jpg'); // Default image
        }

        $breadcrumb = [
            'title' => 'Profil Saya',
            'link'  => route('profil.index'),
            'list'  => [
                ['label' => 'Profil', 'url' => route('profil.index')],
            ],
        ];

        $activeMenu = 'profil';

        return view('profil.index', compact('user', 'breadcrumb', 'activeMenu', 'fotoPath'));
    }

    /**
     * Update informasi profil pengguna
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'nama'      => 'required|string|max:100',
            'username'  => ['required','string','min:4','max:20', Rule::unique('users')->ignore($user->id)],
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        if ($request->filled('password_lama')) {
            $rules['password_lama'] = 'required';
            $rules['password']      = 'required|string|min:6|confirmed';
        }

        $data = $request->validate($rules);

        // Ganti password jika diminta
        if ($request->filled('password_lama')) {
            if (! Hash::check($request->password_lama, $user->password)) {
                return back()
                    ->withErrors(['password_lama' => 'Password lama tidak sesuai'])
                    ->withInput();
            }
            $user->password = Hash::make($data['password']);
        }

        // Simpan foto profil jika diupload
        if ($request->hasFile('foto')) {
            // Nama file akan selalu sama untuk user yang sama
            $filename = 'user_' . $user->id . '.jpg';
            
            // Hapus file lama jika ada
            if (Storage::disk('public')->exists('foto_profil/' . $filename)) {
                Storage::disk('public')->delete('foto_profil/' . $filename);
            }
            
            // Simpan file baru
            $request->file('foto')->storeAs('foto_profil', $filename, 'public');
        }

        // Update data profil lainnya
        $user->nama     = $data['nama'];
        $user->username = $data['username'];
        $user->save();

        return redirect()
            ->route('profil.index')
            ->with('success', 'Profil berhasil diperbarui');
    }
}