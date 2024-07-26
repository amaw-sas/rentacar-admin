<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CategoryMonthPrice>
 */
class CategoryMonthPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id'        =>  Category::factory(),
            '1k_kms'       =>  $this->faker->randomNumber(5),
            '2k_kms'       =>  $this->faker->randomNumber(5),
            '3k_kms'       =>  $this->faker->randomNumber(5),
            'init_date'     =>  $this->faker->unique()->date('Y-m-d'),
            'end_date'      =>  $this->faker->unique()->date('Y-m-d'),
            'total_insurance_price'      =>  $this->faker->randomNumber(5),
            'one_day_price'      =>  $this->faker->randomNumber(5),
        ];
    }
}
