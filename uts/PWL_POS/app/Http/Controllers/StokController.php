<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\StokModel;
use App\Models\BarangModel;
use App\Models\UserModel;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index()
    {
        // Hitung stok seperti sebelumnya
        $stok = DB::table('m_barang')
            ->leftJoin('t_stok', 'm_barang.barang_id', '=', 't_stok.barang_id')
            ->select(
                'm_barang.barang_id',
                'm_barang.barang_kode',
                'm_barang.barang_nama',
                DB::raw('IFNULL(SUM(t_stok.stok_jumlah), 0) as total_stok')
            )
            ->groupBy('m_barang.barang_id', 'm_barang.barang_kode', 'm_barang.barang_nama')
            ->get();
    
        // Tambahkan ini:
        $activeMenu = 'stok';
        $breadcrumb = (object)[
            'title' => 'Stok',
            'list'  => ['Home','Stok']
        ];
    
        // Kirim ketiganya ke view
        return view('stok.index', compact('stok','activeMenu','breadcrumb'));
    }
    
    public function create()
    {
        $barang = BarangModel::all();
        $user    = UserModel::all();
        return view('stok.create', compact('barang','user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id'    => 'required|exists:m_barang,barang_id',
            'user_id'      => 'required|exists:m_user,user_id',
            'stok_tanggal' => 'required|date',
            'stok_jumlah'  => 'required|integer|min:1',
        ]);

        StokModel::create($request->only('barang_id','user_id','stok_tanggal','stok_jumlah'));

        return redirect()->route('stok.index')->with('success','Data stok berhasil ditambahkan');
    }

    // Mengembalikan partial view modal edit
    public function editForm($id)
    {
        $barang = BarangModel::findOrFail($id);
        return view('stok.partials.modal_edit_stok', compact('barang'));
    }

    // Menyimpan perubahan stok via AJAX
    public function update(Request $request)
    {
        $request->validate([
            'barang_id'   => 'required|exists:m_barang,barang_id',
            'stok_jumlah' => 'required|integer',
        ]);

        StokModel::create([
            'barang_id'    => $request->barang_id,
            // gunakan user yg sedang login; ganti '1' jika belum implementasi login
            'user_id'      => auth()->id() ?? 1,
            'stok_tanggal' => now(),
            'stok_jumlah'  => $request->stok_jumlah,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Stok berhasil diperbarui'
        ]);
    }
}