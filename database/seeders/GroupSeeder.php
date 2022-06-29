<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Group::create([
            'name' => 'Group 1',
            'active' => true,
            'company_uuid' => Company::where('name', 'Company 1')->first()->uuid,
            'is_company_leader' => true,
        ]);

        Group::create([
            'name' => 'Group 2',
            'active' => true,
            'company_uuid' => Company::where('name', 'Company 1')->first()->uuid,
        ]);

        Group::create([
            'name' => 'Group 3',
            'active' => true,
            'company_uuid' => Company::where('name', 'Company 2')->first()->uuid,
            'is_company_leader' => true,
        ]);

        Group::create([
            'name' => 'Group 4',
            'active' => true,
            'company_uuid' => Company::where('name', 'Company 2')->first()->uuid,
        ]);


    }
}
