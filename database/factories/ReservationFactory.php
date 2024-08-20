<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Franchise;
use App\Enums\IdentificationType;
use App\Enums\MonthlyMileage;
use App\Enums\ReservationStatus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fullname'              =>  $this->faker->name,
            'identification_type'   =>  (IdentificationType::Cedula)->value,
            'identification'        =>  $this->faker->randomNumber(6, true),
            'phone'                 =>  $this->faker->e164PhoneNumber(),
            'email'                 =>  $this->faker->safeEmail(),
            'category'              =>  Category::factory(),
            'pickup_location'       =>  Branch::factory(),
            'return_location'       =>  Branch::factory(),
            'pickup_date'           =>  $this->faker->date('Y-m-d'),
            'return_date'           =>  $this->faker->date('Y-m-d'),
            'pickup_hour'           =>  $this->faker->time('H:i'),
            'return_hour'           =>  $this->faker->time('H:i'),
            'selected_days'         =>  $this->faker->numberBetween(1, 30),
            'extra_hours'           =>  $this->faker->numberBetween(0, 10),
            'extra_hours_price'     =>  $this->faker->randomNumber(6, true),
            'coverage_days'         =>  $this->faker->numberBetween(0, 10),
            'coverage_price'        =>  $this->faker->randomNumber(6, true),
            'return_fee'            =>  $this->faker->randomNumber(6, true),
            'tax_fee'               =>  $this->faker->randomNumber(6, true),
            'iva_fee'               =>  $this->faker->randomNumber(6, true),
            'total_price'           =>  $this->faker->randomNumber(6, true),
            'total_price_localiza'  =>  $this->faker->randomNumber(6, true),
            'franchise'             =>  Franchise::factory(),
            'user'                  =>  $this->faker->word,
            'reserve_code'          =>  (string) $this->faker->randomNumber(6, true),
            'status'                =>  ($this->faker->randomElement(ReservationStatus::class))->value,
            'monthly_mileage'       =>  ($this->faker->randomElement(MonthlyMileage::class))->value,
            'total_insurance'       =>  $this->faker->boolean(),
            'created_at'            =>  $this->faker->dateTime()->format('Y-m-d H:i:s')
        ];
    }
}
