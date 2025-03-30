<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'penjualan_id' => 1,
                'user_id' => 3,
                'pembeli' => 'Budi',
                'penjualan_kode' => '20220202-1',
                'tanggal_penjualan' => '2022-02-02',
            ],
            [
                'penjualan_id' => 2,
                'user_id' => 3,
                'pembeli' => 'Andi',
                'penjualan_kode' => '20220202-2',
                'tanggal_penjualan' => '2022-02-02',
            ],
            [
                'penjualan_id' => 3,
                'user_id' => 3,
                'pembeli' => 'Cici',
                'penjualan_kode' => '20220202-3',
                'tanggal_penjualan' => '2022-02-02',
            ],
            [
                'penjualan_id' => 4,
                'user_id' => 3,
                'pembeli' => 'Dedi',
                'penjualan_kode' => '20220203-1',
                'tanggal_penjualan' => '2022-02-03',
            ],
            [
                'penjualan_id' => 5,
                'user_id' => 3,
                'pembeli' => 'Harvey',
                'penjualan_kode' => '20220203-2',
                'tanggal_penjualan' => '2022-02-03',
            ],
            [
                'penjualan_id' => 6,
                'user_id' => 3,
                'pembeli' => 'Helena',
                'penjualan_kode' => '20220203-3',
                'tanggal_penjualan' => '2022-02-03',
            ],
            [
                'penjualan_id' => 7,
                'user_id' => 3,
                'pembeli' => 'Irene',
                'penjualan_kode' => '20220204-1',
                'tanggal_penjualan' => '2022-02-04',
            ],
            [
                'penjualan_id' => 8,
                'user_id' => 3,
                'pembeli' => 'John',
                'penjualan_kode' => '20220204-2',
                'tanggal_penjualan' => '2022-02-04',
            ],
            [
                'penjualan_id' => 9,
                'user_id' => 3,
                'pembeli' => 'Kevin',
                'penjualan_kode' => '20220204-3',
                'tanggal_penjualan' => '2022-02-04',
            ],
            [
                'penjualan_id' => 10,
                'user_id' => 3,
                'pembeli' => 'Lily',
                'penjualan_kode' => '20220205-1',
                'tanggal_penjualan' => '2022-02-05',
            ]
        ];
        DB::table('t_penjualan')->insert($data);
    }
}
