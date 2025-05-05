<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Anggota;
use Illuminate\Support\Str;

class AnggotaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $anggotas = [];
        for ($i = 1; $i <= 10; $i++) {
            $nim = sprintf('123140%03d', $i);
             // Membuat NIM: 123140001, 123140002, dst.
            $anggotas[] = [
                'nim' => $nim,
                'nama' => 'Anggota ' . Str::random(8), // Nama acak, misalnya: Anggota Xyz123
                'status_anggota' => 'anggota_muda',
                'angkatan' => 2023,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Anggota::insert($anggotas);
    }
}