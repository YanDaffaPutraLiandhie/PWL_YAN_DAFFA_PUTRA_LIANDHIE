<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Illuminate\Http\Request;
use Illuminate\support\Facades\DB;
use Monolog\Level;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class LevelController extends Controller
{
        // public function index()
        // {
            // DB::insert('insert into m_level(level_kode, level_nama, created_at) values(?, ? ,?)', ['CUS', 'Pelanggan', now()]);
            // return 'Insert data baru berhasil';

            // $row = DB::update('update m_level set level_nama = ? where level_kode = ?', ['Customer', 'CUS']);
            // return 'Update data berhasil. Jumlah data yang diupdate: '. $row. 'baris';

            // $row = DB::delete('delete from m_level where level_kode = ?', ['CUS']);
            // return 'Delete data berhasil. Jumlah data yang dihapus: ' . $row. ' baris';

            // $data = DB::select ('select * from m_level');
            // return view('level', ['data' => $data]);
        // }

        // menampilkan halaman awal user
        public function index()
        {
            $breadcrumb = (object)[
                'title' => 'Daftar Level',
                'list'  => ['Home', 'Level']
            ];
    
            $page = (object)[
                'title' => 'Daftar level yang terdaftar dalam sistem'
            ];
    
            $activeMenu = 'level'; // set menu yang sedang aktif
    
            $level = LevelModel::all();     //ambil data level untuk filter level
    
            return view('level.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
        }

    // Ambil data level dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $levels = LevelModel::select('level_id', 'level_kode', 'level_nama');

        return DataTables::of($levels)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            // menambahkan kolom aksi
            ->addColumn('aksi', function ($level) {
                // $btn = '<a href="' . url('/level/' . $level->level_id) . '" class="btn btn-info btn-sm">Detail</a>';
                // $btn .= ' <a href="' . url('/level/' . $level->level_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a>';
                // $btn .= ' <form class="d-inline-block" method="POST" action="' . url('/level/' . $level->level_id) . '">'
                //     . csrf_field()
                //     . method_field('DELETE')
                //     . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\')">Hapus</button>'
                //     . '</form>';

                // JS6 - Tugas(m_level) 
                $btn  = '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> '; 
                $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/delete_ajax').'\')"  class="btn btn-danger btn-sm">Hapus</button> '; 

                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

// Menampilkan halaman form tambah user 
public function create(){
    $breadcrumb = (object)[
        'title' => 'Tambah Level',
        'list' => ['Home', 'Level', 'Tambah']
    ];

    $page = (object)[
        'title' => 'Tambah level baru'
    ];

    $activeMenu = 'level'; // set menu ysng sedang aktif

    return view('level.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
}

// menyimpan data level baru 
public function store(Request $request)
{
    $request->validate([
        // username harus diisi, berupa string, minimal 10 karakter dan bernilai ditabel m_level kolom username
        'level_kode' => 'required|string|max:10|unique:m_level,level_kode',
        'level_nama' => 'required|string|max:100' // level_nama harus diisi, berupa string, dan maksimal 100 karakter
    ]);

    LevelModel::create([
        'level_kode'  => $request->level_kode,
        'level_nama'  => $request->level_nama
    ]);

    return redirect('/level')-> with('succes', 'Data level berhasil disimpan');
}

// menampilkan detail
public function show(string $id)
{
$level = LevelModel::find($id);

$breadcrumb = (object)[
    'title' => 'Detail Level',
    'list' => ['Home', 'Level', 'Detail']
];
$page = (object)[
    'title' => 'Detail Level'
    ];
    $activeMenu = 'level';   // set menu yang aktif

    return view('level.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
}

// menampilkan halaman form edit level
public function edit(string $id)
{
    $level = LevelModel::find($id);

    $breadcrumb = (object)[
        'title' => 'Edit level',
        'list' => ['Home', 'Level', 'Edit']
    ];

    $page = (object)[
        'title' => 'Edit level'
    ];

    $activeMenu = 'level';   // set menu yang sedang aktif

    return view('level.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level,'activeMenu' => $activeMenu]);
}

// menyimpan perubahan data user
public function update(Request $request, string $id)
{
    $request->validate([
        // level_kode harus diisi, berupa string, minimal 10 karakter,
        // dan bernilai unik ditabel m_level kolom username untuk level dengan id yang sedang diedit
        'level_kode' => 'required|string|max:10|unique:m_level,level_kode,'.$id. ',level_id',
        'level_nama' => 'required|string|max:100'    // level_nama harus diisi, berupa string, dan maksimal 100 karakter

    ]);

    LevelModel::find($id)->update([
        'level_kode' => $request->level_kode,
        'level_nama' => $request->level_nama,
    ]);

    return redirect('/level')->with('success', 'Data level berhasil diubah');
}

// menghapus data level 
public function destroy(string $id)
{
    $check = LevelModel::find($id);
    if (!$check) {  // untuk mengecek apakah data level dengan id yang dimaksud ada atau tidak
        return redirect('/level')->with('error', 'Data level tidak ditemukan');
    }

    try {
        LevelModel::destroy($id);    // hapus data level

        return redirect('/level')->with('success', 'Data level berhasil dihapus');
    } catch (\Illuminate\Database\QueryException $e) {
        //Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
        return redirect('/level')->with('error', 'Data gagal dihapus karena masuh terdapat tabel lain yang terkait dengan data ini');
    }
}

// Tugas Jobsheet 6
public function create_ajax() {
    return view('level.create_ajax');
}

public function store_ajax(Request $request) {
    // cek apakah request berupa ajax
    if($request->ajax() || $request->wantsJson()) {
        $rules = [
            'level_kode'  => 'required|string|max:10|unique:m_level,level_kode',     //level_kode harus diisi, berupa string, maks 10 karakter, bernilai unik di tabel m_level kolom level_kode
            'level_nama'  => 'required|string|max:100',   //level_nama harus diisi, berupa string, maks 100 karakter
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return response()->json([
                'status' => false, // response status, false: error/gagal, true: berhasil
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors(), // pesan error validasi
            ]);
        }

        LevelModel::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Data level berhasil disimpan'
        ]);
    }

    redirect('/');
} 

// JS6 - Tugas(m_level) 
//menampilkan halaman form edit level ajax
public function edit_ajax(string $id) {
    $level = LevelModel::find($id);

    return view('level.edit_ajax', ['level' => $level]);
}

public function update_ajax(Request $request, $id)
{
    // cek apakah request dari ajax
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
           'level_kode'  => 'required|string|max:10|unique:m_level,level_kode,' . $id . ',level_id',     
           'level_nama'  => 'required|string|max:100',  
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false, // respon json, true: berhasil, false: gagal
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors() // menunjukkan field mana yang error
            ]);
        }

        $level = LevelModel::find($id);
        if ($level) {
            $level->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diupdate'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
    return redirect('/');
}

// JS6 - Tugas(m_level)
public function confirm_ajax(string $id) {
    $level = LevelModel::find($id);

    return view('level.confirm_ajax', ['level' => $level]);
 }

// JS6 - Tugas(m_level)
public function delete_ajax(Request $request, $id)
{
    // cek apakah request dari ajax
    if ($request->ajax() || $request->wantsJson()) {
        $level = LevelModel::find($id);
        if ($level) {
            $level->delete();
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
    return redirect('/');
}
}