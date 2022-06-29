<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create([
            'name' => 'Company 1',
            'active' => true,
            'created_by' => User::where('name', 'Admin')->first()->id,
        ]);

        Company::create([
            'name' => 'Company 2',
            'active' => true,
            'created_by' => User::where('name', 'Admin')->first()->id,
        ]);

    }
}
