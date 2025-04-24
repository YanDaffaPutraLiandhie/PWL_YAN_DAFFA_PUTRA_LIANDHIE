<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\LevelModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BarangController extends Controller
{
    // Menampilkan halaman awal user
    public function index()
    {
        $activeMenu = 'barang';

        $breadcrumb = (object)[
            'title' => 'Data Barang',
            'list'  => ['Home', 'Barang']
        ];

        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();

        return view('barang.index', [
            'activeMenu' => $activeMenu,
            'breadcrumb' => $breadcrumb,
            'kategori'   => $kategori
        ]);
    }

    // Ambil data barang dalam bentuk json untuk DataTables
    public function list(Request $request)
{
    $barangs = BarangModel::select('barang_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual', 'kategori_id')
        ->with('kategori');

    $kategori_id = $request->input('filter_kategori');
    if (!empty($kategori_id)) {
        $barangs->where('kategori_id', $kategori_id);
    }

    return DataTables::of($barangs)
        ->addIndexColumn()
        ->addColumn('aksi', function ($barang) {
            $btn  = ''; // Hapus tombol Detail
            $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
            $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';
            return $btn;
        })
        ->rawColumns(['aksi'])
        ->make(true);
}


    // Menampilkan halaman form tambah kategori
    public function create()
    {
        $breadcrumb = (object)[
            'title' => 'Tambah barang',
            'list' => ['Home', 'Barang', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah barang baru'
        ];

        $kategori = KategoriModel::all();
        $activeMenu = 'barang';

        return view('barang.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    // Menyimpan data kategori baru
    public function store(Request $request)
    {
        $request->validate([
            'barang_kode' => 'required|string|max:10|unique:m_barang,barang_kode',
            'barang_nama' => 'required|string|max:100',
            'harga_jual'  => 'required|integer',
            'harga_beli'  => 'required|integer',
            'kategori_id' => 'required|integer',
        ]);

        BarangModel::create([
            'barang_kode'  => $request->barang_kode,
            'barang_nama'  => $request->barang_nama,
            'harga_jual'   => $request->harga_jual,
            'harga_beli'   => $request->harga_beli,
            'kategori_id'  => $request->kategori_id
        ]);

        return redirect('/barang')->with('success', 'Data barang berhasil disimpan');
    }

    // Menampilkan detail barang
    public function show(string $id)
    {
        $barang = BarangModel::with('kategori')->find($id);

        $breadcrumb = (object)[
            'title' => 'Detail barang',
            'list' => ['Home', 'Barang', 'Detail']
        ];

        $page = (object)[
            'title' => 'Detail Barang'
        ];

        $activeMenu = 'barang';

        return view('barang.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'activeMenu' => $activeMenu]);
    }

    // Menampilkan halaman form edit barang
    public function edit(string $id)
    {
        $barang = BarangModel::find($id);
        $kategori = KategoriModel::all();

        $breadcrumb = (object)[
            'title' => 'Edit Barang',
            'list' => ['Home', 'Barang', 'Edit']
        ];

        $page = (object)[
            'title' => 'Edit Barang'
        ];

        $activeMenu = 'barang';

        return view('barang.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'activeMenu' => $activeMenu, 'kategori' => $kategori]);
    }

    // Menyimpan perubahan data barang
    public function update(Request $request, string $id)
    {
        $request->validate([
            'barang_kode'   => 'required|string|max:10|unique:m_barang,barang_kode,' . $id . ',barang_id',
            'barang_nama'   => 'required|string|max:100',
            'harga_jual'    => 'required|integer',
            'harga_beli'    => 'required|integer',
            'kategori_id'   => 'required|integer',
        ]);

        BarangModel::find($id)->update([
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_jual'  => $request->harga_jual,
            'harga_beli'  => $request->harga_beli,
            'kategori_id' => $request->kategori_id,
        ]);

        return redirect('/barang')->with('success', 'Data barang berhasil diubah');
    }

    // Menghapus data barang
    public function destroy(string $id)
    {
        $check = BarangModel::find($id);
        if (!$check) {
            return redirect('/barang')->with('error', 'Data kategori tidak ditemukan');
        }

        try {
            BarangModel::destroy($id);

            return redirect('/barang')->with('success', 'Data kategori berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/barang')->with('error', 'Data gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
    public function export_excel()
    {
        // 1. Ambil semua barang beserta relasi kategori (untuk memastikan kategori_id valid)
        $barangs = BarangModel::all();

        // 2. Buat objek Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 3. Header kolom sesuai format import
        $sheet->fromArray([
            ['barang_kode', 'barang_nama', 'harga_beli', 'harga_jual', 'kategori_id']
        ], null, 'A1');

        // 4. Isi data mulai baris ke-2
        $row = 2;
        foreach ($barangs as $b) {
            $sheet->setCellValue("A{$row}", $b->barang_kode);
            $sheet->setCellValue("B{$row}", $b->barang_nama);
            $sheet->setCellValue("C{$row}", $b->harga_beli);
            $sheet->setCellValue("D{$row}", $b->harga_jual);
            $sheet->setCellValue("E{$row}", $b->kategori_id);
            $row++;
        }

        // 5. Auto-size kolom agar lebar sesuai konten
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // 6. Prepare writer dan nama file
        $writer   = new Xlsx($spreadsheet);
        $filename = 'template-barang-' . date('Ymd_His') . '.xlsx';

        // 7. Stream download ke browser
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control'       => 'max-age=0',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
    // Form tambah barang AJAX
    public function create_ajax()
    {
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
        return view('barang.create_ajax')->with('kategori', $kategori);
    }

    // Menyimpan barang via AJAX
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_id' => ['required', 'integer', 'exists:m_kategori,kategori_id'],
                'barang_kode' => ['required', 'min:3', 'max:20', 'unique:m_barang,barang_kode'],
                'barang_nama' => ['required', 'string', 'max:100'],
                'harga_beli'  => ['required', 'numeric'],
                'harga_jual'  => ['required', 'numeric'],
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            BarangModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data barang berhasil disimpan'
            ]);
        }

        return redirect('/');
    }

    // Menampilkan halaman form edit barang AJAX
    public function edit_ajax(string $id)
    {
        $barang = BarangModel::find($id);
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();

        return view('barang.edit_ajax', ['barang' => $barang, 'kategori' => $kategori]);
    }

    // Update barang via AJAX
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_id' => ['required', 'integer', 'exists:m_kategori,kategori_id'],
                'barang_kode' => ['required', 'min:3', 'max:20', 'unique:m_barang,barang_kode,' . $id . ',barang_id'],
                'barang_nama' => ['required', 'string', 'max:100'],
                'harga_beli'  => ['required', 'numeric'],
                'harga_jual'  => ['required', 'numeric'],
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $check = BarangModel::find($id);
            if ($check) {
                $check->update($request->all());
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

    // Konfirmasi penghapusan barang via AJAX
    public function confirm_ajax(string $id)
    {
        $barang = BarangModel::find($id);
        return view('barang.confirm_ajax', ['barang' => $barang]);
    }

    // Menghapus barang via AJAX
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $barang = BarangModel::find($id);
            if ($barang) {
                $barang->delete();
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

    // Form Import barang
    public function import()
    {
        return view('barang.import');
    }

    // Mengimpor barang via AJAX
    public function import_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        // Validate the uploaded file
        $rules = [
            'file' => 'required|file|mimes:xlsx,xls|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi file gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        try {
            // Load the Excel file
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
            
            // Remove header row
            array_shift($rows);
            
            if (empty($rows)) {
                return response()->json([
                    'status' => false,
                    'message' => 'File Excel tidak berisi data.'
                ]);
            }

            // Start database transaction
            DB::beginTransaction();
            
            $successCount = 0;
            $errorRows = [];
            $data = [];
            
            // Process each row
            foreach ($rows as $index => $row) {
                $rowNum = $index + 2; // +2 because we removed header and Excel starts from 1
                
                // Check if row has all required columns
                if (!isset($row[0], $row[1], $row[2], $row[3], $row[4]) || empty($row[0])) {
                    $errorRows[] = "Baris {$rowNum}: Data tidak lengkap";
                    continue;
                }
                
                // Validate the data
                $rowData = [
                    'barang_kode' => trim($row[0]),
                    'barang_nama' => trim($row[1]),
                    'harga_beli'  => (float)$row[2],
                    'harga_jual'  => (float)$row[3],
                    'kategori_id' => (int)$row[4],
                ];
                
                // Check if barang_kode already exists
                $existingBarang = BarangModel::where('barang_kode', $rowData['barang_kode'])->first();
                if ($existingBarang) {
                    $errorRows[] = "Baris {$rowNum}: Kode barang '{$rowData['barang_kode']}' sudah ada dalam database";
                    continue;
                }
                
                // Check if kategori_id exists
                $kategoriExists = DB::table('kategori')->where('id', $rowData['kategori_id'])->exists();
                if (!$kategoriExists) {
                    $errorRows[] = "Baris {$rowNum}: Kategori dengan ID {$rowData['kategori_id']} tidak ditemukan";
                    continue;
                }
                
                // Validate prices
                if ($rowData['harga_beli'] <= 0 || $rowData['harga_jual'] <= 0) {
                    $errorRows[] = "Baris {$rowNum}: Harga harus lebih dari 0";
                    continue;
                }
                
                // Add created_at and updated_at timestamps
                $rowData['created_at'] = now();
                $rowData['updated_at'] = now();
                
                $data[] = $rowData;
                $successCount++;
            }
            
            // Insert data if there are valid rows
            if (!empty($data)) {
                BarangModel::insert($data);
                DB::commit();
                
                return response()->json([
                    'status' => true,
                    'message' => "{$successCount} data barang berhasil diimpor." . 
                                 (!empty($errorRows) ? " {$successCount} data berhasil, " . count($errorRows) . " data gagal." : ""),
                    'errors' => $errorRows
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data valid untuk diimpor.',
                    'errors' => $errorRows
                ]);
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error importing barang data: ' . $e->getMessage());
            
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage()
            ]);
        }
    }

    return redirect('/');
}
}
