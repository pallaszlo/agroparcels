<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('seasons')->insert([
            "name" => "Winter",
            'from' => '2021-01-1',
            'to' => '2022-02-28'
        ]);
        DB::table('seasons')->insert([
            "name" => "Spring",
            'from' => '2022-03-1',
            'to' => '2022-05-30'
        ]);
        DB::table('seasons')->insert([
            "name" => "Summer",
            'from' => '2022-06-1',
            'to' => '2022-08-31'
        ]);
        DB::table('seasons')->insert([
            "name" => "Fall",
            'from' => '2022-09-1',
            'to' => '2022-11-30'
        ]);

    }
}
