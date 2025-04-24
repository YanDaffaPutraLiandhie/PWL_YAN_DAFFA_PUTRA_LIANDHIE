<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StokSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('t_stok')->insert([
            ['stok_id' => 1, 'barang_id' => 1, 'stok_jumlah' => 10, 'stok_tanggal' => Carbon::now(), 'user_id' => 1],
            ['stok_id' => 2, 'barang_id' => 2, 'stok_jumlah' => 15, 'stok_tanggal' => Carbon::now(), 'user_id' => 2],
            ['stok_id' => 3, 'barang_id' => 3, 'stok_jumlah' => 20, 'stok_tanggal' => Carbon::now(), 'user_id' => 3],
            ['stok_id' => 4, 'barang_id' => 4, 'stok_jumlah' => 8, 'stok_tanggal' => Carbon::now(), 'user_id' => 1],
            ['stok_id' => 5, 'barang_id' => 1, 'stok_jumlah' => 5, 'stok_tanggal' => Carbon::now(), 'user_id' => 2],
            ['stok_id' => 6, 'barang_id' => 2, 'stok_jumlah' => 10, 'stok_tanggal' => Carbon::now(), 'user_id' => 3],
            ['stok_id' => 7, 'barang_id' => 3, 'stok_jumlah' => 12, 'stok_tanggal' => Carbon::now(), 'user_id' => 1], 
            ['stok_id' => 8, 'barang_id' => 4, 'stok_jumlah' => 7, 'stok_tanggal' => Carbon::now(), 'user_id' => 2], 
            ['stok_id' => 9, 'barang_id' => 1, 'stok_jumlah' => 8, 'stok_tanggal' => Carbon::now(), 'user_id' => 3], 
            ['stok_id' => 10, 'barang_id' => 2, 'stok_jumlah' => 18, 'stok_tanggal' => Carbon::now(), 'user_id' => 1], 
        ]);
    }
}