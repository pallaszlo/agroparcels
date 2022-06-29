<?php

namespace Database\Seeders;

use App\Models\Disease;
use Illuminate\Database\Seeder;

class DiseaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Disease::create([
            'name' => 'Disease 1',
        ]);

        Disease::create([
            'name' => 'Disease 2',
        ]);

        Disease::create([
            'name' => 'Disease 3',
        ]);

        Disease::create([
            'name' => 'Disease 4',
        ]);

        Disease::create([
            'name' => 'Disease 5',
        ]);

        Disease::create([
            'name' => 'Disease 6',
        ]);

        Disease::create([
            'name' => 'Disease 7',
        ]);

        Disease::create([
            'name' => 'Disease 8',
        ]);
    }
}
