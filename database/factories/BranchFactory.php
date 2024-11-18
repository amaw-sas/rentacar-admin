<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\City;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branch>
 */
class BranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'city_id'   =>  City::factory(),
            'code' =>  $this->faker->unique()->word(5),
            'name' =>  $this->faker->sentence(),
            'pickup_address'  => $this->faker->streetAddress(),
            'return_address'  => $this->faker->streetAddress(),
            'pickup_map'  => $this->faker->url(),
            'return_map'  => $this->faker->url(),
        ];
    }
}
