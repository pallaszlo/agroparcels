<?php

namespace Database\Seeders;

use App\Models\Culture;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class CultureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Culture::create([
            'name' => 'Wheat',
            'icon' => 'mdi-mdiWheat',
            'unit_id' => Unit::where('sign', 'kg')->first()->id,
        ]);

        Culture::create([
            'name' => 'Barley',
            'icon' => 'mdi-mdiBarley',
            'unit_id' => Unit::where('sign', 'kg')->first()->id,
        ]);

        Culture::create([
            'name' => 'Rice',
            'icon' => 'mdi-mdiRice',
            'unit_id' => Unit::where('sign', 'kg')->first()->id,
        ]);

        Culture::create([
            'name' => 'Oats',
            'icon' => 'mdi-mdiOats',
            'unit_id' => Unit::where('sign', 'kg')->first()->id,
        ]);

        Culture::create([
            'name' => 'Millet',
            'icon' => 'mdi-mdiMillet',
            'unit_id' => Unit::where('sign', 'kg')->first()->id,
        ]);

        Culture::create([
            'name' => 'Sorghum',
            'icon' => 'mdi-mdiSorghum',
            'unit_id' => Unit::where('sign', 'kg')->first()->id,
        ]);

        Culture::create([
            'name' => 'Maize',
            'icon' => 'mdi-mdiMaize',
            'unit_id' => Unit::where('sign', 'kg')->first()->id,
        ]);

        Culture::create([
            'name' => 'Soybean',
            'icon' => 'mdi-mdiSoybean',
            'unit_id' => Unit::where('sign', 'kg')->first()->id,
        ]);


    }
}
