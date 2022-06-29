<?php

namespace Database\Factories;

use App\Models\FitoProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class FitoProductFactory extends Factory
{
    protected $model = FitoProduct::class;
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
