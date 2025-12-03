<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Tutor
        $tutor = [
            ['nama' => 'Parto','mapel' => 'Sosiologi','jns_kel' => 'Laki-laki','no_hp' => mt_rand(1, 999999999999)],
            ['nama' => 'Cassa','mapel' => 'Olahraga','jns_kel' => 'Perempuan','no_hp' => mt_rand(1, 999999999999)],
            ['nama' => 'Griffith','mapel' => 'Psikologis','jns_kel' => 'Laki-laki','no_hp' => mt_rand(1, 999999999999)],
            ['nama' => 'Qua Xin','mapel' => 'Bahasa Mandarin','jns_kel' => 'Perempuan','no_hp' => mt_rand(1, 999999999999)],
        ];
        foreach ($tutor as $data) {
            Tutor::create($data);
        }


        // 2. Kelas
        $kelas = [
            ['id_kelas' => 'PC','nama' => 'Private Class', 'deskripsi' => 'Kelas private dengan 1 murid dan 1 tutor', 'harga' => 45000],
            ['id_kelas' => 'SPC','nama' => 'Semi Private Class', 'deskripsi' => 'Kelas semi private dengan 2 murid dan 1 tutor', 'harga' => 50000],
            ['id_kelas' => 'CPC','nama' => 'Combo Private Class', 'deskripsi' => 'Kelas combo private dengan 4 murid dan 1 tutor', 'harga' => 56000],
        ];
        foreach ($kelas as $data) {
            Kelas::create($data);
        }
    }
}
