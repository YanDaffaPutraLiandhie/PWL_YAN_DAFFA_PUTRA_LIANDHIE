<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void 
    { 
        $data = [ 
            ['penjualan_id' => 1, 'user_id' => 1, 'pembeli' => 'Rafli', 'penjualan_kode' => str()->random(10), 'penjualan_tanggal' => Carbon::parse('2024-04-01 09:30:00')], 
            ['penjualan_id' => 2, 'user_id' => 1, 'pembeli' => 'Lukman', 'penjualan_kode' => str()->random(10), 'penjualan_tanggal' => Carbon::parse('2024-04-02 11:15:00')], 
            ['penjualan_id' => 3, 'user_id' => 1, 'pembeli' => 'Lehman', 'penjualan_kode' => str()->random(10), 'penjualan_tanggal' => Carbon::parse('2024-04-03 13:45:00')], 
            ['penjualan_id' => 4, 'user_id' => 1, 'pembeli' => 'Herman', 'penjualan_kode' => str()->random(10), 'penjualan_tanggal' => Carbon::parse('2024-04-04 08:20:00')], 
            ['penjualan_id' => 5, 'user_id' => 1, 'pembeli' => 'Kenan', 'penjualan_kode' => str()->random(10), 'penjualan_tanggal' => Carbon::parse('2024-04-05 14:10:00')], 
            ['penjualan_id' => 6, 'user_id' => 1, 'pembeli' => 'Syahrul', 'penjualan_kode' => str()->random(10), 'penjualan_tanggal' => Carbon::parse('2024-04-06 17:30:00')], 
            ['penjualan_id' => 7, 'user_id' => 1, 'pembeli' => 'Gayuh', 'penjualan_kode' => str()->random(10), 'penjualan_tanggal' => Carbon::parse('2024-04-07 10:00:00')], 
            ['penjualan_id' => 8, 'user_id' => 1, 'pembeli' => 'Petrus', 'penjualan_kode' => str()->random(10), 'penjualan_tanggal' => Carbon::parse('2024-04-08 12:45:00')], 
            ['penjualan_id' => 9, 'user_id' => 1, 'pembeli' => 'Yan', 'penjualan_kode' => str()->random(10), 'penjualan_tanggal' => Carbon::parse('2024-04-09 15:20:00')], 
            ['penjualan_id' => 10, 'user_id' => 1, 'pembeli' => 'Jinan', 'penjualan_kode' => str()->random(10), 'penjualan_tanggal' => Carbon::parse('2024-04-10 09:55:00')], 
        ]; 

        DB::table('t_penjualan')->insert($data); 
    }
}
