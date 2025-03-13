<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id' => 1, 'nama' => 'Elektronik', 'deskripsi' => 'Kategori barang elektronik'],
            ['id' => 2, 'nama' => 'Pakaian', 'deskripsi' => 'Kategori pakaian'],
            ['id' => 3, 'nama' => 'Makanan', 'deskripsi' => 'Kategori makanan'],
            ['id' => 4, 'nama' => 'Minuman', 'deskripsi' => 'Kategori minuman'],
            ['id' => 5, 'nama' => 'Perabotan', 'deskripsi' => 'Kategori perabotan'],
        ];
        DB::table('m_kategori')->insert($data);
    }
}
