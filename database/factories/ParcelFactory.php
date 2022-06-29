<?php

namespace Database\Factories;

use App\Models\Parcel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParcelFactory extends Factory
{
    protected $model = Parcel::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'name' => $this->faker->text(10),
            'identifier' => $this->faker->text(256),
            'data' => json_encode(["key" => $this->faker->randomNumber()])
        ];
    }
}
