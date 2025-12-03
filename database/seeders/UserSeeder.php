<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tutor = [
            [
                'nama' => 'Tutor1',
                'mapel' => 'Mtk',
                'jns_kel' => 'Pria',
                'no_hp' => mt_rand(1,9999999999)
            ],
            [
                'nama' => 'Tutor2',
                'mapel' => 'Mtk',
                'jns_kel' => 'Pria',
                'no_hp' => mt_rand(1,9999999999)
            ],
            [
                'nama' => 'Tutor3',
                'mapel' => 'Mtk',
                'jns_kel' => 'Pria',
                'no_hp' => mt_rand(1,9999999999)
            ],
        ];

        $roles = [
            [
                'nama' => 'super-admin',
                'desc' => 'Memiliki semua akses',
            ],
            [
                'nama' => 'tutor',
                'desc' => 'Melihat data penggajian',
            ],
        ];
        foreach ($roles as $s) {
            Role::create($s);
        }

        $user = [
            [
                'username' => 'pemilik',
                'email' => 'pemilik@gmail.com',
                'role' => 'super-admin'
            ],
            [
                'username' => 'tutor_1',
                'email' => 'tutor@gmail.com',
                'role' => 'tutor'
            ],
            [
                'username' => 'tutor_2',
                'email' => 'tutor2@gmail.com',
                'role' => 'tutor'
            ],
        ];

        foreach ($user as $s => $data) {
            $roles = Role::where('nama', $data['role'])->first();

            User::create([
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => bcrypt('12345678'),
                'role' => $roles->id,
            ]);

            Tutor::create($tutor);
        }
    }
}
