<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Person::create([
            'name' => 'Kis Pista',
            'group_uuid' => Group::where('name', 'Group 1')->first()->uuid,
            'user_id'  => User::where('name', 'Admin1')->first()->id,
        ]);

        Person::create([
            'name' => 'Nagy Lajos',
            'group_uuid' => Group::where('name', 'Group 3')->first()->uuid,
            'user_id'  => User::where('name', 'Admin2')->first()->id,
        ]);

        Person::create([
            'name' => 'Munkas Pista',
            'group_uuid' => Group::where('name', 'Group 1')->first()->uuid,
            'user_id'  => User::where('name', 'User1')->first()->id,
        ]);

    }
}
