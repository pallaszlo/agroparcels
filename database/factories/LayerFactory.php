<?php

namespace Database\Factories;

use App\Models\Layer;
use App\Models\Season;
use Illuminate\Database\Eloquent\Factories\Factory;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Grimzy\LaravelMysqlSpatial\Types\Polygon;
use Grimzy\LaravelMysqlSpatial\Types\LineString;

class LayerFactory extends Factory
{

    protected $model = Layer::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $polygon =  new Polygon([new LineString([
            new Point( 45.8858261, 25.8825874),
            new Point( 45.8867223, 25.8906555),
            new Point( 45.8703498, 25.8912563),
            new Point( 45.8704095, 25.8813000),
            new Point( 45.8858261, 25.8825874)
        ])]);
        return [
            'name' => $this->faker->word(),
            'data' => $polygon,
            //'default' => rand(0,1),
        ];
    }
}
