<?php

namespace Database\Seeders;

use App\Models\Pasien;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        for ($i = 6; $i <= 20; $i++) {
            Pasien::create([
                'no_rekam_medis' => 'RM'.str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama_pasien'    => 'Pasien '.$i,
                'tanggal_lahir'  => fake()->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d'),
                'jenis_kelamin'  => fake()->randomElement(['L', 'P']),
                'alamat'         => fake()->streetAddress().', Sidoarjo',
                'no_hp'          => '08'.fake()->numerify('##########'),
            ]);
        }
    }
}
