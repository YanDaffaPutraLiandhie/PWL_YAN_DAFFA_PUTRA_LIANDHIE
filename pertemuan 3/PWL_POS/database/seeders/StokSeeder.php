<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void 
    { 
        $data = [ 
            ['stok_id' => 1, 'barang_id' => 1, 'stok_jumlah' => 50, 'user_id' => 1, 'stok_tanggal' => Carbon::now()], 
            ['stok_id' => 2, 'barang_id' => 2, 'stok_jumlah' => 30, 'user_id' => 1, 'stok_tanggal' => Carbon::now()], 
            ['stok_id' => 3, 'barang_id' => 3, 'stok_jumlah' => 20, 'user_id' => 1, 'stok_tanggal' => Carbon::now()], 
            ['stok_id' => 4, 'barang_id' => 4, 'stok_jumlah' => 100, 'user_id' => 1, 'stok_tanggal' => Carbon::now()], 
            ['stok_id' => 5, 'barang_id' => 5, 'stok_jumlah' => 15, 'user_id' => 1, 'stok_tanggal' => Carbon::now()], 
            ['stok_id' => 6, 'barang_id' => 6, 'stok_jumlah' => 60, 'user_id' => 1, 'stok_tanggal' => Carbon::now()], 
            ['stok_id' => 7, 'barang_id' => 7, 'stok_jumlah' => 80, 'user_id' => 1, 'stok_tanggal' => Carbon::now()], 
            ['stok_id' => 8, 'barang_id' => 8, 'stok_jumlah' => 40, 'user_id' => 1, 'stok_tanggal' => Carbon::now()], 
            ['stok_id' => 9, 'barang_id' => 9, 'stok_jumlah' => 25, 'user_id' => 1, 'stok_tanggal' => Carbon::now()], 
            ['stok_id' => 10, 'barang_id' => 10, 'stok_jumlah' => 10, 'user_id' => 1, 'stok_tanggal' => Carbon::now()], 
        ]; 

        DB::table('t_stok')->insert($data); 
    }
}
