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
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'slug' => Str::slug('Admin'),
            'password' => Hash::make('password'),
            'lokasi' => 'fleksible',
            'role_id' => 1,
        ]);


        User::create([
            'name' => 'Dosen Prodi 1',
            'email' => 'dosen1@example.com',
            'slug' => Str::slug('Dosen Prodi 1'),
            'password' => Hash::make('password'),
            'lokasi' => 'fleksible',
            'role_id' => 4,
        ]);

        User::create([
            'name' => 'Dosen Prodi 2',
            'email' => 'dosen2@example.com',
            'slug' => Str::slug('Dosen Prodi 2'),
            'password' => Hash::make('password'),
            'lokasi' => 'fleksible',
            'role_id' => 4,
        ]);

        User::create([
            'name' => 'Dosen Prodi 3',
            'email' => 'dosen3@example.com',
            'slug' => Str::slug('Dosen Prodi 3'),
            'password' => Hash::make('password'),
            'lokasi' => 'fleksible',
            'role_id' => 4,
        ]);


        User::create([
            'name' => 'Mahasiswa',
            'email' => 'mahasiswa@example.com',
            'slug' => Str::slug('Mahasiswa'),
            'password' => Hash::make('password'),
            'lokasi' => 'fleksible',
            'role_id' => 5,
        ]);

        User::create([
            'name' => 'Laboran',
            'email' => 'laboran@example.com',
            'slug' => Str::slug('Laboran'),
            'password' => Hash::make('password'),
            'lokasi' => 'viktor',
            'role_id' => 2,
        ]);

        User::create([
            'name' => 'Lembaga',
            'email' => 'lembaga@example.com',
            'slug' => Str::slug('Lembaga'),
            'password' => Hash::make('password'),
            'lokasi' => 'fleksible',
            'role_id' => 3,
        ]);
    }
}
