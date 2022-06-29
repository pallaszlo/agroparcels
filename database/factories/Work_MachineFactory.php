<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\Work_Machine;
use Illuminate\Database\Eloquent\Factories\Factory;

class Work_MachineFactory extends Factory
{
    protected $model = Work_Machine::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'group_uuid' => Group::inRandomOrder()->first()->uuid,
        ];
    }
}
