<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::create([
            'name' => 'Not Started',
            'is_in_progress' => false,
            'is_completed' => false,
        ]);
        Status::create([
            'name' => 'In Progress',
            'is_in_progress' => true,
            'is_completed' => false,
        ]);
        Status::create([
            'name' => 'Completed',
            'is_in_progress' => false,
            'is_completed' => true,
        ]);
    }
}
