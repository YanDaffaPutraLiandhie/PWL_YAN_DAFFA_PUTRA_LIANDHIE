<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'kategori_id' => 1,
                'kategori_kode' => 'ELEC',
                'kategori_nama' => 'Elektronik'
            ],
            [
                'kategori_id' => 2,
                'kategori_kode' => 'DRINK',
                'kategori_nama' => 'Minuman'
            ],
            [
                'kategori_id' => 3,
                'kategori_kode' => 'FOOD',
                'kategori_nama' => 'Makanan'
            ],
            [
                'kategori_id' => 4,
                'kategori_kode' => 'TOOL',
                'kategori_nama' => 'Alat'
            ],
            [
                'kategori_id' => 5,
                'kategori_kode' => 'EXEC',
                'kategori_nama' => 'Olahraga'
            ],
        ];

        DB::table('m_kategori')->insert($data);
    }
}