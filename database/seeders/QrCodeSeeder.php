<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use App\Models\QrCode;

class QrCodeSeeder extends Seeder
{
    public function run()
    {
        $qrcode = [];
        for ($i = 1; $i <= 10; $i++) {
            $nim = sprintf('123140%03d', $i);
            $nim_encrypted = Crypt::encryptString($nim); // Enkripsi NIM
             // Membuat NIM: 123140001, 123140002, dst.
            $qrcode[] = [
                'anggota_id' => $i,
                'qrcode' => $nim_encrypted, // Simpan NIM terenkripsi
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        QrCode::insert($qrcode);
    }
}
