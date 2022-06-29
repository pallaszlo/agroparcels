<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Unit::create([
            'name' => 'liter',
            'sign' => 'l',
        ]);

        Unit::create([
            'name' => 'kilogram',
            'sign' => 'kg',
        ]);

        Unit::create([
            'name' => 'darab',
            'sign' => 'db',
        ]);
    }
}
