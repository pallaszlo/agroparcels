<?php

namespace Database\Factories;

use App\Models\Culture;
use App\Models\Disease;
use App\Models\FitoProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class TreatmentFactory extends Factory
{
    protected $model = \App\Models\Treatment::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'fitoproduct_uuid' => FitoProduct::inRandomOrder()->first()->uuid,
            'disease_uuid' => Disease::inRandomOrder()->first()->uuid,
            'culture_uuid' => Culture::inRandomOrder()->first()->uuid,
        ];
    }
}
