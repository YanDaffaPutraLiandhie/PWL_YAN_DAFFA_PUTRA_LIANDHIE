<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'supplier_kode' => 'SPL01',
                'supplier_nama' => 'PT Semeru Jaya',
                'supplier_alamat' => 'Jl. Raya No. 1 malang',
            ],
            [
                'supplier_kode' => 'SPL02',
                'supplier_nama' => 'PT Amerta',
                'supplier_alamat' => 'Jl. Pendidikan No. 19 malang',
            ],
            [
                'supplier_kode' => 'SPL03',
                'supplier_nama' => 'PT Jaya Abadi',
                'supplier_alamat' => 'Jl. kedawung No. 2 malang',
            ],
        ];
        DB::table('m_supplier')->insert($data);
    }
}
