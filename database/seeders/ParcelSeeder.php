<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Parcel;
use Illuminate\Database\Seeder;

class ParcelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Parcel::create([
            'name' => 'Parcel 1',
            'group_uuid' => Group::where('name', 'Group 1')->first()->uuid,
            'identifier' => '',
            'data' => json_encode(["key" => ''])
        ]);

        Parcel::create([
            'name' => 'Parcel 2',
            'group_uuid' => Group::where('name', 'Group 1')->first()->uuid,
            'identifier' => '',
            'data' => json_encode(["key" => ''])
        ]);

        Parcel::create([
            'name' => 'Parcel 3',
            'group_uuid' => Group::where('name', 'Group 1')->first()->uuid,
            'identifier' => '',
            'data' => json_encode(["key" => ''])
        ]);

        Parcel::create([
            'name' => 'Parcel 4',
            'group_uuid' => Group::where('name', 'Group 2')->first()->uuid,
            'identifier' => '',
            'data' => json_encode(["key" => ''])
        ]);

        Parcel::create([
            'name' => 'Parcel 5',
            'group_uuid' => Group::where('name', 'Group 2')->first()->uuid,
            'identifier' => '',
            'data' => json_encode(["key" => ''])
        ]);

        Parcel::create([
            'name' => 'Parcel 6',
            'group_uuid' => Group::where('name', 'Group 3')->first()->uuid,
            'identifier' => '',
            'data' => json_encode(["key" => ''])
        ]);

        Parcel::create([
            'name' => 'Parcel 7',
            'group_uuid' => Group::where('name', 'Group 3')->first()->uuid,
            'identifier' => '',
            'data' => json_encode(["key" => ''])
        ]);

        Parcel::create([
            'name' => 'Parcel 8',
            'group_uuid' => Group::where('name', 'Group 4')->first()->uuid,
            'identifier' => '',
            'data' => json_encode(["key" => ''])
        ]);
    }
}
