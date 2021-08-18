<?php

namespace Database\Factories;

use App\Models\Offer;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfferFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Offer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(){
        return [
            'city_id' => $this->faker->numberBetween($min = 1, $max = 1000),
            'company_id' => $this->faker->numberBetween($min = 1, $max = 100),
            "latitude" => $this->faker->latitude($min = -90, $max = 90),
            "longitude" => $this->faker->longitude($min = -180, $max = 180),
            'radius' => $this->faker->numerify('######'),
            'currency_id' =>  $this->faker->numberBetween($min = 1, $max = 1),
            'price' =>  $this->faker->numberBetween($min = 1500, $max = 6000),
        ];
    }
}
