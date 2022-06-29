<?php

namespace Database\Factories;

use App\Models\Season;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeasonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Season::class;
    public function definition()
    {
        return [
            //
        ];
    }

    public function name($name)
    {
        return $this->state(function ($attributes) use ($name) {
            return [
                'name' => $name
            ];
        });
    }

}
