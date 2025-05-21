<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::create([
            'nama_pengguna' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'lokasi_id' => 1,
            'role_id' => 1,
        ]);


        User::create([
            'nama_pengguna' => 'Dosen Prodi 1',
            'email' => 'dosen1@example.com',
            'password' => Hash::make('password'),
            'lokasi_id' => 1,
            'role_id' => 4,
        ]);

        User::create([
            'nama_pengguna' => 'Dosen Prodi 2',
            'email' => 'dosen2@example.com',
            'password' => Hash::make('password'),
            'lokasi_id' => 1,
            'role_id' => 4,
        ]);

        User::create([
            'nama_pengguna' => 'Dosen Prodi 3',
            'email' => 'dosen3@example.com',
            'password' => Hash::make('password'),
            'lokasi_id' => 1,
            'role_id' => 4,
        ]); 

        

        User::create([
            'nama_pengguna' => 'Mahasiswa',
            'email' => 'mahasiswa@example.com',
            'password' => Hash::make('password'),
            'lokasi_id' => 1,
            'role_id' => 5,
        ]);

        User::create([
            'nama_pengguna' => 'Laboran',
            'email' => 'laboran@example.com',
            'password' => Hash::make('password'),
            'lokasi_id' => 2,
            'role_id' => 2,
        ]);

        User::create([
            'nama_pengguna' => 'Laboran 2',
            'email' => 'laboran2@example.com',
            'password' => Hash::make('password'),
            'lokasi_id' => 3,
            'role_id' => 2,
        ]);

        User::create([
            'nama_pengguna' => 'Laboran 3',
            'email' => 'laboran3@example.com',
            'password' => Hash::make('password'),
            'lokasi_id' => 4,
            'role_id' => 2,
        ]);

        User::create([
            'nama_pengguna' => 'Lembaga',
            'email' => 'lembaga@example.com',
            'password' => Hash::make('password'),
            'lokasi_id' => 1,
            'role_id' => 3,
        ]);
    }
}
