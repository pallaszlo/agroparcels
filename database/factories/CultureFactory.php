<?php

namespace Database\Factories;

use App\Models\Culture;
use Illuminate\Database\Eloquent\Factories\Factory;

class CultureFactory extends Factory
{
    protected $model = Culture::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
        ];
    }
}
