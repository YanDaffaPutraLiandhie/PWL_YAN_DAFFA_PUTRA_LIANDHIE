<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['id' => 1, 'barang_kode' => 'BAR001', 'barang_nama' => 'Komputer Gaming', 'kategori_id' => 1, 'harga_beli' => 10000000, 'harga_jual' => 13000000],
            ['id' => 2, 'barang_kode' => 'BAR002', 'barang_nama' => 'Jaket', 'kategori_id' => 2, 'harga_beli' => 150000, 'harga_jual' => 350000],
            ['id' => 3, 'barang_kode' => 'BAR003', 'barang_nama' => 'Coklat ', 'kategori_id' => 3, 'harga_beli' => 20000, 'harga_jual' => 25000],
            ['id' => 4, 'barang_kode' => 'BAR004', 'barang_nama' => 'Kopi ', 'kategori_id' => 4, 'harga_beli' => 40000, 'harga_jual' => 60000],
            ['id' => 5, 'barang_kode' => 'BAR005', 'barang_nama' => 'Tablet', 'kategori_id' => 1, 'harga_beli' => 5000000, 'harga_jual' => 6500000],
            ['id' => 6, 'barang_kode' => 'BAR006', 'barang_nama' => 'Kursi ', 'kategori_id' => 5, 'harga_beli' => 300000, 'harga_jual' => 550000],
            ['id' => 7, 'barang_kode' => 'BAR007', 'barang_nama' => 'Sneakers', 'kategori_id' => 2, 'harga_beli' => 250000, 'harga_jual' => 400000],
            ['id' => 8, 'barang_kode' => 'BAR008', 'barang_nama' => 'Kacang', 'kategori_id' => 3, 'harga_beli' => 10000, 'harga_jual' => 20000],
            ['id' => 9, 'barang_kode' => 'BAR009', 'barang_nama' => 'Jus Jeruk', 'kategori_id' => 4, 'harga_beli' => 2000, 'harga_jual' => 3000],
            ['id' => 10, 'barang_kode' => 'BAR010', 'barang_nama' => 'Drum ', 'kategori_id' => 5, 'harga_beli' => 600000, 'harga_jual' => 850000],
        ];

        DB::table('m_barang')->insert($data);
    }
}
