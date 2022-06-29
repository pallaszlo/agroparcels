<?php

namespace Database\Seeders;

use App\Models\FitoProduct;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class FitoProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FitoProduct::create([
            'name' => 'FitoProduct 1',
            'unit_id' => Unit::inRandomOrder()->first()->id,
            'icon' => 'a',
        ]);

        FitoProduct::create([
            'name' => 'FitoProduct 2',
            'unit_id' => Unit::inRandomOrder()->first()->id,
            'icon' => 'b',
        ]);

        FitoProduct::create([
            'name' => 'FitoProduct 3',
            'unit_id' => Unit::inRandomOrder()->first()->id,
            'icon' => 'c',
        ]);

        FitoProduct::create([
            'name' => 'FitoProduct 4',
            'unit_id' => Unit::inRandomOrder()->first()->id,
            'icon' => 'd',
        ]);

        FitoProduct::create([
            'name' => 'FitoProduct 5',
            'unit_id' => Unit::inRandomOrder()->first()->id,
            'icon' => 'e',
        ]);
    }
}
