<?php

namespace Database\Seeders;

use App\Models\Layer;
use App\Models\Operation;
use App\Models\Season;
use App\Models\Status;
use App\Models\Treatment;
use App\Models\Work;
use Illuminate\Database\Seeder;

class OperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Operation::create([
            'work_uuid' => Work::inRandomOrder()->first()->uuid,
            'layer_uuid' => Layer::inRandomOrder()->first()->uuid,
            'season_id' => Season::inRandomOrder()->first()->id,
            'status_id' => Status::where('is_in_progress', false)->first()->id,
            'treatment_uuid' => Treatment::inRandomOrder()->first()->uuid,
            'treatment_quantity' => rand(100, 300)
        ]);

        Operation::create([
            'work_uuid' => Work::inRandomOrder()->first()->uuid,
            'layer_uuid' => Layer::inRandomOrder()->first()->uuid,
            'season_id' => Season::inRandomOrder()->first()->id,
            'status_id' => Status::where('is_in_progress', false)->first()->id,
            'treatment_uuid' => Treatment::inRandomOrder()->first()->uuid,
            'treatment_quantity' => rand(100, 300)
        ]);

        Operation::create([
            'work_uuid' => Work::inRandomOrder()->first()->uuid,
            'layer_uuid' => Layer::inRandomOrder()->first()->uuid,
            'season_id' => Season::inRandomOrder()->first()->id,
            'status_id' => Status::where('is_in_progress', false)->first()->id,
            'treatment_uuid' => Treatment::inRandomOrder()->first()->uuid,
            'treatment_quantity' => rand(100, 300)
        ]);

        Operation::create([
            'work_uuid' => Work::inRandomOrder()->first()->uuid,
            'layer_uuid' => Layer::inRandomOrder()->first()->uuid,
            'season_id' => Season::inRandomOrder()->first()->id,
            'status_id' => Status::where('is_in_progress', false)->first()->id,
            'treatment_uuid' => Treatment::inRandomOrder()->first()->uuid,
            'treatment_quantity' => rand(100, 300)
        ]);

        Operation::create([
            'work_uuid' => Work::inRandomOrder()->first()->uuid,
            'layer_uuid' => Layer::inRandomOrder()->first()->uuid,
            'season_id' => Season::inRandomOrder()->first()->id,
            'status_id' => Status::where('is_in_progress', false)->first()->id,
            'treatment_uuid' => Treatment::inRandomOrder()->first()->uuid,
            'treatment_quantity' => rand(100, 300)
        ]);

        Operation::create([
            'work_uuid' => Work::inRandomOrder()->first()->uuid,
            'layer_uuid' => Layer::inRandomOrder()->first()->uuid,
            'season_id' => Season::inRandomOrder()->first()->id,
            'status_id' => Status::where('is_in_progress', false)->first()->id,
            'treatment_uuid' => Treatment::inRandomOrder()->first()->uuid,
            'treatment_quantity' => rand(100, 300)
        ]);

        Operation::create([
            'work_uuid' => Work::inRandomOrder()->first()->uuid,
            'layer_uuid' => Layer::inRandomOrder()->first()->uuid,
            'season_id' => Season::inRandomOrder()->first()->id,
            'status_id' => Status::where('is_in_progress', false)->first()->id,
            'treatment_uuid' => Treatment::inRandomOrder()->first()->uuid,
            'treatment_quantity' => rand(100, 300)
        ]);

        Operation::create([
            'work_uuid' => Work::inRandomOrder()->first()->uuid,
            'layer_uuid' => Layer::inRandomOrder()->first()->uuid,
            'season_id' => Season::inRandomOrder()->first()->id,
            'status_id' => Status::where('is_in_progress', false)->first()->id,
            'treatment_uuid' => null,
            'treatment_quantity' => null
        ]);

        Operation::create([
            'work_uuid' => Work::inRandomOrder()->first()->uuid,
            'layer_uuid' => Layer::inRandomOrder()->first()->uuid,
            'season_id' => Season::inRandomOrder()->first()->id,
            'status_id' => Status::where('is_in_progress', false)->first()->id,
            'treatment_uuid' => null,
            'treatment_quantity' => null
        ]);
    }
}
