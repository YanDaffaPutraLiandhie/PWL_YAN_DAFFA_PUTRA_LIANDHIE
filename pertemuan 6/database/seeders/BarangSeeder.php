<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'barang_id' => 1,
                'kategori_id' => 1,
                'barang_kode' => 'ELK01',
                'barang_nama' => 'Laptop',
                'harga_jual' => 5000000,
                'harga_beli' => 4000000,
            ],
            [
                'barang_id' => 2,
                'kategori_id' => 1,
                'barang_kode' => 'ELK02',
                'barang_nama' => 'Monitor',
                'harga_jual' => 3000000,
                'harga_beli' => 2000000,
            ],
            [
                'barang_id' => 3,
                'kategori_id' => 2,
                'barang_kode' => 'FSH01',
                'barang_nama' => 'Kemeja',
                'harga_jual' => 150000,
                'harga_beli' => 100000,
            ],
            [
                'barang_id' => 4,
                'kategori_id' => 2,
                'barang_kode' => 'FSH02',
                'barang_nama' => 'Celana',
                'harga_jual' => 200000,
                'harga_beli' => 150000,
            ],
            [
                'barang_id' => 5,
                'kategori_id' => 3,
                'barang_kode' => 'MKN01',
                'barang_nama' => 'Nasi Goreng',
                'harga_jual' => 15000,
                'harga_beli' => 10000,
            ],
            [
                'barang_id' => 6,
                'kategori_id' => 3,
                'barang_kode' => 'MKN02',
                'barang_nama' => 'Mie Goreng',
                'harga_jual' => 20000,
                'harga_beli' => 15000,
            ],
            [
                'barang_id' => 7,
                'kategori_id ' => 4,
                'barang_kode' => 'RMH01',
                'barang_nama' => 'Sabun Mandi',
                'harga_jual' => 20000,
                'harga_beli' => 15000,
            ],
            [
                'barang_id' => 8,
                'kategori_id' => 4,
                'barang_kode' => 'RMH02',
                'barang_nama' => 'Pasta Gigi',
                'harga_jual' => 30000,
                'harga_beli' => 25000,
            ],
            [
                'barang_id' => 9,
                'kategori_id' => 5,
                'barang_kode' => 'MDO01',
                'barang_nama' => 'Diecast Mobil',
                'harga_jual' => 50000,
                'harga_beli' => 40000,
            ],
            [
                'barang_id' => 10,
                'kategori_id' => 5,
                'barang_kode' => 'MDO02',
                'barang_nama' => 'Bola Basket',
                'harga_jual' => 60000,
                'harga_beli' => 50000,
            ],
        ];
        DB::table('m_barang')->insert($data);
    }
}
