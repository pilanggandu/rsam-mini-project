<?php

namespace Database\Seeders;

use App\Models\Pasien;
use Illuminate\Database\Seeder;

class PasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pasiens = [
            [
                'no_rekam_medis' => 'RM0001',
                'nama_pasien'    => 'Budi Santoso',
                'tanggal_lahir'  => '1985-03-12',
                'jenis_kelamin'  => 'L',
                'alamat'         => 'Perum Gading Fajar Blok A-12, Sidoarjo',
                'no_hp'          => '081234567001',
            ],
            [
                'no_rekam_medis' => 'RM0002',
                'nama_pasien'    => 'Siti Aminah',
                'tanggal_lahir'  => '1990-07-25',
                'jenis_kelamin'  => 'P',
                'alamat'         => 'Jl. Raya Taman No. 15, Taman, Sidoarjo',
                'no_hp'          => '081234567002',
            ],
            [
                'no_rekam_medis' => 'RM0003',
                'nama_pasien'    => 'Ahmad Fauzi',
                'tanggal_lahir'  => '1978-11-03',
                'jenis_kelamin'  => 'L',
                'alamat'         => 'Perum Pondok Jati Blok C-7, Sidoarjo',
                'no_hp'          => '081234567003',
            ],
            [
                'no_rekam_medis' => 'RM0004',
                'nama_pasien'    => 'Dewi Lestari',
                'tanggal_lahir'  => '1995-02-18',
                'jenis_kelamin'  => 'P',
                'alamat'         => 'Jl. Pahlawan No. 21, Sidoarjo Kota',
                'no_hp'          => '081234567004',
            ],
            [
                'no_rekam_medis' => 'RM0005',
                'nama_pasien'    => 'Rudi Hartono',
                'tanggal_lahir'  => '1982-09-09',
                'jenis_kelamin'  => 'L',
                'alamat'         => 'Perum Delta Sari Indah Blok D-3, Waru, Sidoarjo',
                'no_hp'          => '081234567005',
            ],
        ];

        foreach ($pasiens as $pasien) {
            Pasien::create($pasien);
        }
    }
}
