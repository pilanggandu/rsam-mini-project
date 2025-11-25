<?php

namespace Database\Seeders;

use App\Models\Obat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ObatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $obats = [
            [
                'kode_obat'  => 'PARA500',
                'nama_obat'  => 'Paracetamol 500 mg Tablet',
                'stok'       => 200,
                'harga_jual' => 1500, // Rp 1.500 / tablet
            ],
            [
                'kode_obat'  => 'AMOX500',
                'nama_obat'  => 'Amoxicillin 500 mg Kapsul',
                'stok'       => 150,
                'harga_jual' => 2500, // Rp 2.500 / kapsul
            ],
            [
                'kode_obat'  => 'CTM4',
                'nama_obat'  => 'CTM 4 mg Tablet',
                'stok'       => 300,
                'harga_jual' => 1000,
            ],
            [
                'kode_obat'  => 'OMEP20',
                'nama_obat'  => 'Omeprazole 20 mg Kapsul',
                'stok'       => 100,
                'harga_jual' => 3500,
            ],
            [
                'kode_obat'  => 'RAN150',
                'nama_obat'  => 'Ranitidine 150 mg Tablet',
                'stok'       => 120,
                'harga_jual' => 2000,
            ],
            [
                'kode_obat'  => 'IBUP400',
                'nama_obat'  => 'Ibuprofen 400 mg Tablet',
                'stok'       => 180,
                'harga_jual' => 2500,
            ],
            [
                'kode_obat'  => 'ANTASID',
                'nama_obat'  => 'Antasida DOEN Tablet Kunyah',
                'stok'       => 220,
                'harga_jual' => 1500,
            ],
            [
                'kode_obat'  => 'SALBUT',
                'nama_obat'  => 'Salbutamol 2 mg Tablet',
                'stok'       => 90,
                'harga_jual' => 3000,
            ],
        ];

        foreach ($obats as $data) {
            Obat::updateOrCreate(
                ['kode_obat' => $data['kode_obat']],
                $data
            );
        }
    }
}
