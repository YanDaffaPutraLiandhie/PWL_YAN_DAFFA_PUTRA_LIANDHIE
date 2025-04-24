<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\BarangModel;
use App\Models\StokModel;
use App\Models\PenjualanModel;
use App\Models\PenjualanDetailModel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;


class PenjualanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Penjualan',
            'list' => ['Home', 'Penjualan']
        ];

        $page = (object) [
            'title' => 'Daftar Penjualan',
        ];

        $activeMenu = 'penjualan';

        return view('penjualan.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    /**
     * Download data penjualan ke Excel.
     */
    public function export_excel()
    {
        // Ambil semua detail penjualan beserta relasinya
        $details = PenjualanDetailModel::with([
            'penjualan.user',    // untuk data pembeli & penginput
            'barang.kategori'    // untuk nama & kategori barang
        ])->get();

        // Buat Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->fromArray([
            ['No', 'Tanggal', 'Kode', 'Pembeli', 'Penginput', 'Barang', 'Kategori', 'Harga Satuan', 'Jumlah', 'Subtotal']
        ], null, 'A1');

        // Isi data
        $row = 2;
        $no = 1;
        foreach ($details as $d) {
            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValue("B{$row}", $d->penjualan->penjualan_tanggal);
            $sheet->setCellValue("C{$row}", $d->penjualan->penjualan_kode);
            $sheet->setCellValue("D{$row}", $d->penjualan->pembeli);
            $sheet->setCellValue("E{$row}", $d->penjualan->user->username);
            $sheet->setCellValue("F{$row}", $d->barang->barang_nama);
            $sheet->setCellValue("G{$row}", $d->barang->kategori->kategori_nama);
            $sheet->setCellValue("H{$row}", $d->barang->harga_jual);
            $sheet->setCellValue("I{$row}", $d->jumlah);
            $sheet->setCellValue("J{$row}", $d->jumlah * $d->barang->harga_jual);
            $row++;
        }

        // Auto-size kolom
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Setup writer dan nama file
        $writer   = new Xlsx($spreadsheet);
        $filename = 'data-penjualan-' . date('Ymd_His') . '.xlsx';

        // Kirim ke browser sebagai download
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control'       => 'max-age=0',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function list(Request $request)
    {
        $penjualan = PenjualanModel::select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
            ->with('user')
            ->orderBy('created_at', 'desc');


        return DataTables::of($penjualan)
            ->addIndexColumn()
            ->addColumn('total_harga', function ($penjualan) { // menambahkan kolom total harga
                return 'Rp. ' . number_format($penjualan->total_harga, 0, ',', '.');
            })
            ->addColumn('aksi', function ($penjualan) { // menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm m-1">Hapus</button> ';

                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function show_ajax(string $id)
    {
        $penjualan = PenjualanModel::with('user', 'penjualan_detail')->find($id);

        if (!$penjualan) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return view('penjualan.detail', [
            'penjualan' => $penjualan
        ]);
    }

    public function create()
    {
        $breadcrumb = (object)['title' => 'Tambah Penjualan', 'list' => ['Home', 'Penjualan', 'Tambah']];
        $page       = (object)['title' => 'Tambah Penjualan'];
        $activeMenu = 'penjualan';

        $barang = BarangModel::all()->map(fn($b) => [
            'barang_id'      => $b->barang_id,
            'barang_nama'    => $b->barang_nama,
            'harga_jual'     => $b->harga_jual,
            'stok_available' => $b->stok_available,
        ]);

        return view('penjualan.create', compact('breadcrumb', 'page', 'activeMenu', 'barang'));
    }


    public function store(Request $request)
    {
        $rules = [
            'pembeli'               => 'required|string|max:255',
            'penjualan_tanggal'     => 'required|date',
            'barang'                => 'required|array|min:1',
            'barang.*.id'           => 'required|exists:m_barang,barang_id',
            'barang.*.quantity'     => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $penjualan = PenjualanModel::create([
                    'user_id'           => auth()->id(),
                    'pembeli'           => $request->pembeli,
                    'penjualan_tanggal' => $request->penjualan_tanggal,
                    'penjualan_kode'    => 'P' . time() . strtoupper(str()->random(6)),
                ]);

                foreach ($request->barang as $item) {
                    $b = BarangModel::find($item['id']);

                    if ($b->stok_available < $item['quantity']) {
                        throw new \Exception("Stok tidak cukup untuk {$b->barang_nama}");
                    }

                    $penjualan->penjualan_detail()->create([
                        'barang_id' => $item['id'],
                        'jumlah'    => $item['quantity'],
                        'harga'     => $b->harga_jual * $item['quantity'],
                    ]);

                    StokModel::create([
                        'barang_id'    => $b->barang_id,
                        'user_id'      => auth()->id(),
                        'stok_tanggal' => now(),
                        'stok_jumlah'  => -$item['quantity'],
                    ]);
                }
            });

            return redirect()->route('penjualan.index')
                ->with('success', 'Penjualan berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['msg' => $e->getMessage()])->withInput();
        }
    }
    public function edit(string $id)
    {
        $breadcrumb = (object) [
            'title' => 'Edit Penjualan',
            'list' => ['Home', 'Penjualan', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Penjualan',
        ];

        $activeMenu = 'penjualan';

        $penjualan = PenjualanModel::with('user', 'penjualan_detail')->find($id);

        if (!$penjualan) {
            return redirect('/penjualan');
        }

        $penjualanWithBarang = $penjualan->penjualan_detail->map(function ($item) {
            return [
                'barang_id' => $item->barang_id,
                'barang_nama' => $item->barang->barang_nama,
                'harga_jual' => $item->barang->harga_jual,
                'jumlah' => $item->jumlah,
                'stok_available' => $item->barang->stok_available,
                'total_harga' => $item->harga,
            ];
        });

        // dd($penjualanWithBarang);

        $barang = BarangModel::select('barang_nama', 'harga_jual', 'barang_id')
            ->get()
            ->map(function ($barang) {
                return [
                    'barang_id' => $barang->barang_id,
                    'barang_nama' => $barang->barang_nama,
                    'harga_jual' => $barang->harga_jual,
                    'stok_available' => $barang->stok_available,
                ];
            });

        return view('penjualan.edit_ajax', compact('breadcrumb', 'page', 'activeMenu'))->with([
            'penjualan' => $penjualan,
            'barang' => $barang,
            'penjualan_detail' => $penjualanWithBarang,
        ]);
    }
    public function edit_ajax(string $id)
    {
        // Ambil data penjualan berdasarkan ID
        $penjualan = PenjualanModel::with('user', 'penjualan_detail')->find($id);

        // Cek apakah penjualan ditemukan
        if (!$penjualan) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        // Ambil data barang untuk ditampilkan di form edit
        $barang = BarangModel::all()->map(fn($b) => [
            'barang_id'      => $b->barang_id,
            'barang_nama'    => $b->barang_nama,
            'harga_jual'     => $b->harga_jual,
            'stok_available' => $b->stok_available,
        ]);

        // Kembalikan tampilan edit dengan data penjualan dan barang
        return view('penjualan.edit_ajax', [
            'penjualan' => $penjualan,
            'barang' => $barang,
        ]);
    }
    public function update(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'penjualan_id' => 'required|exists:t_penjualan,penjualan_id',
                'pembeli' => 'required|string|max:255',
                'penjualan_tanggal' => 'required|date',
                'barang' => 'required|array|min:1',
                'barang.*.id' => 'required|exists:m_barang,barang_id',
                'barang.*.quantity' => 'required|integer|min:1',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => "Validasi gagal",
                    'msgField' => $validator->errors()
                ]);
            }

            DB::beginTransaction();
            try {
                $penjualan = PenjualanModel::find($request->penjualan_id);

                if (!$penjualan) {
                    return response()->json([
                        'status' => false,
                        'message' => "Data tidak ditemukan"
                    ]);
                }

                $penjualan->update([
                    'pembeli' => $request->pembeli,
                    'penjualan_tanggal' => $request->penjualan_tanggal,
                ]);

                // Hapus detail penjualan yang sudah ada
                $penjualan->penjualan_detail()->delete();

                foreach ($request->barang as $item) {
                    $barang = BarangModel::find($item['id']);

                    // cek stock barang
                    if ($barang->stock_available < $item['quantity']) {

                        DB::rollBack();
                        return response()->json([
                            'status' => false,
                            'message' => "Stock " . $barang->barang_nama . " tidak cukup"
                        ]);
                    }

                    $penjualan->penjualan_detail()->create([
                        'penjualan_id' => $penjualan->penjualan_id,
                        'barang_id' => $item['id'],
                        'jumlah' => $item['quantity'],
                        'harga' => $barang->harga_jual * $item['quantity'],
                    ]);
                }

                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Penjualan berhasil diupdate'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data',
                    'error' => $e->getMessage()
                ]);
            }
        }
        redirect('/penjualan');
    }



    public function confirm_ajax(string $id)
    {
        $penjualan = PenjualanModel::with('user', 'penjualan_detail')->find($id);

        if (!$penjualan) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return view('penjualan.confirm_ajax', [
            'penjualan' => $penjualan
        ]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $penjualan = PenjualanModel::with('penjualan_detail')->find($id);

                if ($penjualan) {


                    $penjualan->penjualan_detail()->delete();
                    $penjualan->delete();

                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus'
                    ]);

                    if ($penjualan->trashed()) {
                        return response()->json([
                            'status' => true,
                            'message' => 'Data berhasil dihapus'
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'Data gagal dihapus'
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data tidak ditemukan'
                    ]);
                }
            } catch (QueryException $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data gagal dihapus, karena masih digunakan'
                ]); {
                }
            }
        }

        return redirect('/');
    }
}
