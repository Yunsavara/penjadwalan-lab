<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LaboratoriumUnpamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $labs = [
            ['name' => 'CBT 1', 'lokasi' => 'Lantai 1', 'kapasitas' => 30, 'jenislab_id' => 1],
            ['name' => 'CBT 2', 'lokasi' => 'Lantai 1', 'kapasitas' => 30, 'jenislab_id' => 1],
            ['name' => 'CBT 3', 'lokasi' => 'Lantai 2', 'kapasitas' => 30, 'jenislab_id' => 1],
            ['name' => 'CBT 4', 'lokasi' => 'Lantai 2', 'kapasitas' => 30, 'jenislab_id' => 1],
            ['name' => 'CBT 5', 'lokasi' => 'Lantai 3', 'kapasitas' => 30, 'jenislab_id' => 1],
            ['name' => 'CBT 6', 'lokasi' => 'Lantai 3', 'kapasitas' => 30, 'jenislab_id' => 1],
            ['name' => 'CBT 7', 'lokasi' => 'Lantai 4', 'kapasitas' => 30, 'jenislab_id' => 1],
            ['name' => 'CBT 8', 'lokasi' => 'Lantai 4', 'kapasitas' => 30, 'jenislab_id' => 1],
            
            ['name' => 'TI 1', 'lokasi' => 'Lantai 1', 'kapasitas' => 25, 'jenislab_id' => 1],
            ['name' => 'TI 2', 'lokasi' => 'Lantai 1', 'kapasitas' => 25, 'jenislab_id' => 1],
            ['name' => 'TI 3', 'lokasi' => 'Lantai 2', 'kapasitas' => 25, 'jenislab_id' => 1],

            ['name' => 'Jaringan 1', 'lokasi' => 'Lantai 3', 'kapasitas' => 20, 'jenislab_id' => 2],
            ['name' => 'Jaringan 2', 'lokasi' => 'Lantai 3', 'kapasitas' => 20, 'jenislab_id' => 2],
            ['name' => 'Jaringan 3', 'lokasi' => 'Lantai 4', 'kapasitas' => 20, 'jenislab_id' => 2],

            ['name' => 'Elektro 1', 'lokasi' => 'Lantai 1', 'kapasitas' => 15, 'jenislab_id' => 3],
            ['name' => 'Elektro 2', 'lokasi' => 'Lantai 1', 'kapasitas' => 15, 'jenislab_id' => 3],
            ['name' => 'Elektro 3', 'lokasi' => 'Lantai 2', 'kapasitas' => 15, 'jenislab_id' => 3],

            ['name' => 'Multimedia 1', 'lokasi' => 'Lantai 3', 'kapasitas' => 25, 'jenislab_id' => 4],
            ['name' => 'Multimedia 2', 'lokasi' => 'Lantai 3', 'kapasitas' => 25, 'jenislab_id' => 4],
        ];

        foreach ($labs as $lab) {
            DB::table('laboratorium_unpams')->insert([
                'name' => $lab['name'],
                'slug' => Str::slug($lab['name']),
                'lokasi' => $lab['lokasi'],
                'kapasitas' => $lab['kapasitas'],
                'status' => 'tersedia',
                'jenislab_id' => $lab['jenislab_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
