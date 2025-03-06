<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Penjualan_detailSeeder extends Seeder
{
    /**
     * Jalankan database seeder.
     */
    public function run(): void 
    { 
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('t_penjualan_detail')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $data = [
            ['penjualan_id' => 1, 'barang_id' => 1, 'harga' => 50000, 'jumlah' => 2],
            ['penjualan_id' => 1, 'barang_id' => 2, 'harga' => 75000, 'jumlah' => 1],
            ['penjualan_id' => 2, 'barang_id' => 3, 'harga' => 60000, 'jumlah' => 3],
            ['penjualan_id' => 3, 'barang_id' => 1, 'harga' => 50000, 'jumlah' => 1],
            ['penjualan_id' => 4, 'barang_id' => 4, 'harga' => 80000, 'jumlah' => 2],
            ['penjualan_id' => 5, 'barang_id' => 5, 'harga' => 90000, 'jumlah' => 1],
            ['penjualan_id' => 6, 'barang_id' => 6, 'harga' => 100000, 'jumlah' => 2],
            ['penjualan_id' => 7, 'barang_id' => 7, 'harga' => 110000, 'jumlah' => 3],
            ['penjualan_id' => 8, 'barang_id' => 8, 'harga' => 120000, 'jumlah' => 2],
            ['penjualan_id' => 9, 'barang_id' => 9, 'harga' => 130000, 'jumlah' => 1],
        ];

        // Insert data ke dalam tabel `t_penjualan_detail`
        DB::table('t_penjualan_detail')->insert($data);
    }
}
