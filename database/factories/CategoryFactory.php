<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * A set of rent category prices
     *
     * @var array
     */
    public $prices = [
        10000,
        20000,
        30000,
        40000,
        15000,
        25000,
        35000,
        45000,
        100000,
        110000,
        120000
    ];



    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() : array
    {

        $name = $this->faker->sentence(4);
        $slug = Str::slug($this->faker->unique()->word(), '-', 'es');

        return [
            'identification'    =>  $slug,
            'name'              =>  $name,
            'category'          =>  $this->faker->word(),
            'description'       =>  $this->faker->sentence(),
            'image'             =>  'carcategories/car.png',
            'ad'           =>  $this->faker->sentence()
        ];
    }
}
