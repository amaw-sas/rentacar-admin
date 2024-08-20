<?php

namespace Tests\Feature;

use App\Enums\IdentificationType;
use App\Enums\MonthlyMileage;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Branch;
use App\Models\City;
use Tests\TestCase;

class PreviewClientMailTest extends TestCase
{
    use RefreshDatabase;

    public $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        session()->flush();

    }

    #[Group("preview_client_mail")]
    #[Test]
    public function check_data_is_rendered(): void
    {
        $category = Category::factory()->hasModels(2)->create([
            'name'  => 'Gama C',
            'category'  => 'Sedán automático',
            'description'  => '3 puertas',
        ]);

        $city = City::factory()->create([
            'name'  => "Cali",
        ]);

        $pickup_branch = Branch::factory()->create([
            'name' => 'Cali Aeropuerto',
            'city_id'   => $city->id
        ]);

        $return_branch = Branch::factory()->create([
            'name' => 'Cali Aeropuerto',
            'city_id'   => $city->id
        ]);

        $date_pickup = now()->addDay();
        $pickup_date = $date_pickup->format('Y-m-d');
        $pickup_date_output = $date_pickup->isoFormat('LL');
        $pickup_hour = '9:00';

        $date_return = now()->addDays(2);
        $return_date = $date_return->format('Y-m-d');
        $return_date_output = $date_return->isoFormat('LL');
        $return_hour = '9:00';

        $reservation = Reservation::factory()->create([
            'category' => $category->id,
            'identification_type' => IdentificationType::CedulaExtranjeria->value,
            'identification' => '11111',
            'fullname'  => 'John Doe',
            'reserve_code'  => 'AV-1',
            'pickup_location'   =>  $pickup_branch->id,
            'pickup_date'   =>  $pickup_date,
            'pickup_hour'   =>  $pickup_hour,
            'return_location'   =>  $return_branch->id,
            'return_date'   =>  $return_date,
            'return_hour'   =>  $return_hour,
            'extra_hours'   => 0,
            'extra_hours_price'   => 0,
            'return_fee'   => 0,
            'coverage_price'   => 1000,
            'tax_fee'   => 1000,
            'iva_fee'   => 1000,
            'total_price'   => 10000,
            'total_price_localiza'   => 10000,
        ]);

        $response = $this
        ->actingAs($this->user)
        ->get(route('reservations.emailPreview', [
            'reservation' => $reservation
        ]))
        ->assertInertia(fn(Assert $page) => $page
            ->component('Reservations/EmailPreview')
            ->has('reservation', fn(Assert $page) => $page
                ->where('fullname', 'John Doe')
                ->where('identification_type', 'C.E.')
                ->where('identification', '11111')
                ->where('reserve_code', 'AV-1')
                ->where('category_name', 'Gama C')
                ->where('category_category', 'Sedán automático')
                ->where('category_description', '3 puertas')
                ->where('category_image', 'http://localhost:8000/storage/carcategories/car.png')
                ->where('pickup_branch', 'Cali Aeropuerto')
                ->where('pickup_city', 'Cali')
                ->where('pickup_date', $pickup_date_output)
                ->where('pickup_hour', '09:00 am')
                ->where('return_branch', 'Cali Aeropuerto')
                ->where('return_city', 'Cali')
                ->where('return_date', $return_date_output)
                ->where('return_hour', '09:00 am')
                ->where('extra_hours', 0)
                ->where('extra_hours_price', $reservation->formatted_extra_hours_price)
                ->where('coverage_price', $reservation->formatted_coverage_price)
                ->where('return_fee', 0)
                ->where('tax_fee', $reservation->formatted_tax_fee_from_localiza_price)
                ->where('iva_fee', $reservation->formatted_iva_fee_from_localiza_price)
                ->where('subtotal_fee', $reservation->formatted_subtotal_from_localiza_price)
                ->where('total_fee', $reservation->formatted_total_price_localiza)
                ->where('base_fee', $reservation->formatted_base_price_from_localiza_price)
                ->where('daily_base_fee', $reservation->formatted_original_vehicle_unit_price)
                ->where('discount_percentage', $reservation->formatted_discount_percentage_from_localiza_price)
                ->where('discount_amount', $reservation->formatted_daily_base_price_from_localiza_price)
                ->etc()
            )
        );


    }

    #[Group("preview_client_mail")]
    #[Test]
    public function check_data_if_included_fees_show_monthly_mileage_one_k_kms_and_total_insurance(): void
    {
        $category = Category::factory()->hasModels(2)->create([
            'name'  => 'Gama C',
            'category'  => 'Sedán automático',
            'description'  => '3 puertas',
        ]);

        $city = City::factory()->create([
            'name'  => "Cali",
        ]);

        $reservation = Reservation::factory()->create([
            'category' => $category->id,
            'selected_days'   =>  30,
            'total_insurance' => true,
            'monthly_mileage' => MonthlyMileage::oneKKms->value,
        ]);

        $response = $this
        ->actingAs($this->user)
        ->get(route('reservations.emailPreview', [
            'reservation' => $reservation
        ]))
        ->assertInertia(fn(Assert $page) => $page
            ->component('Reservations/EmailPreview')
            ->has('reservation', fn(Assert $page) => $page
                ->where('included_fees', "Kilometraje: 1k_kms, Seguro total",)
                ->etc()
            )
        );
    }

    #[Group("preview_client_mail")]
    #[Test]
    public function check_data_if_included_fees_show_monthly_mileage_two_k_kms_and_total_insurance(): void
    {
        $category = Category::factory()->hasModels(2)->create([
            'name'  => 'Gama C',
            'category'  => 'Sedán automático',
            'description'  => '3 puertas',
        ]);

        $city = City::factory()->create([
            'name'  => "Cali",
        ]);

        $reservation = Reservation::factory()->create([
            'category' => $category->id,
            'selected_days'   =>  30,
            'total_insurance' => true,
            'monthly_mileage' => MonthlyMileage::twoKKms->value,
        ]);

        $response = $this
        ->actingAs($this->user)
        ->get(route('reservations.emailPreview', [
            'reservation' => $reservation
        ]))
        ->assertInertia(fn(Assert $page) => $page
            ->component('Reservations/EmailPreview')
            ->has('reservation', fn(Assert $page) => $page
                ->where('included_fees', "Kilometraje: 2k_kms, Seguro total",)
                ->etc()
            )
        );
    }

    #[Group("preview_client_mail")]
    #[Test]
    public function check_data_if_included_fees_show_monthly_mileage_three_k_kms_and_total_insurance(): void
    {
        $category = Category::factory()->hasModels(2)->create([
            'name'  => 'Gama C',
            'category'  => 'Sedán automático',
            'description'  => '3 puertas',
        ]);

        $city = City::factory()->create([
            'name'  => "Cali",
        ]);

        $reservation = Reservation::factory()->create([
            'category' => $category->id,
            'selected_days'   =>  30,
            'total_insurance' => true,
            'monthly_mileage' => MonthlyMileage::threeKKms->value,
        ]);

        $response = $this
        ->actingAs($this->user)
        ->get(route('reservations.emailPreview', [
            'reservation' => $reservation
        ]))
        ->assertInertia(fn(Assert $page) => $page
            ->component('Reservations/EmailPreview')
            ->has('reservation', fn(Assert $page) => $page
                ->where('included_fees', "Kilometraje: 3k_kms, Seguro total",)
                ->etc()
            )
        );
    }

    #[Group("preview_client_mail")]
    #[Test]
    public function check_data_if_included_fees_show_monthly_mileage_one_k_kms_and_basic_insurance(): void
    {
        $category = Category::factory()->hasModels(2)->create([
            'name'  => 'Gama C',
            'category'  => 'Sedán automático',
            'description'  => '3 puertas',
        ]);

        $city = City::factory()->create([
            'name'  => "Cali",
        ]);

        $reservation = Reservation::factory()->create([
            'category' => $category->id,
            'selected_days'   =>  30,
            'total_insurance' => false,
            'monthly_mileage' => MonthlyMileage::oneKKms->value,
        ]);

        $response = $this
        ->actingAs($this->user)
        ->get(route('reservations.emailPreview', [
            'reservation' => $reservation
        ]))
        ->assertInertia(fn(Assert $page) => $page
            ->component('Reservations/EmailPreview')
            ->has('reservation', fn(Assert $page) => $page
                ->where('included_fees', "Kilometraje: 1k_kms, Seguro básico",)
                ->etc()
            )
        );
    }

    #[Group("preview_client_mail")]
    #[Test]
    public function check_data_if_included_fees_show_monthly_mileage_two_k_kms_and_basic_insurance(): void
    {
        $category = Category::factory()->hasModels(2)->create([
            'name'  => 'Gama C',
            'category'  => 'Sedán automático',
            'description'  => '3 puertas',
        ]);

        $city = City::factory()->create([
            'name'  => "Cali",
        ]);

        $reservation = Reservation::factory()->create([
            'category' => $category->id,
            'selected_days'   =>  30,
            'total_insurance' => false,
            'monthly_mileage' => MonthlyMileage::twoKKms->value,
        ]);

        $response = $this
        ->actingAs($this->user)
        ->get(route('reservations.emailPreview', [
            'reservation' => $reservation
        ]))
        ->assertInertia(fn(Assert $page) => $page
            ->component('Reservations/EmailPreview')
            ->has('reservation', fn(Assert $page) => $page
                ->where('included_fees', "Kilometraje: 2k_kms, Seguro básico",)
                ->etc()
            )
        );
    }

    #[Group("preview_client_mail")]
    #[Test]
    public function check_data_if_included_fees_show_monthly_mileage_three_k_kms_and_basic_insurance(): void
    {
        $category = Category::factory()->hasModels(2)->create([
            'name'  => 'Gama C',
            'category'  => 'Sedán automático',
            'description'  => '3 puertas',
        ]);

        $city = City::factory()->create([
            'name'  => "Cali",
        ]);

        $reservation = Reservation::factory()->create([
            'category' => $category->id,
            'selected_days'   =>  30,
            'total_insurance' => false,
            'monthly_mileage' => MonthlyMileage::threeKKms->value,
        ]);

        $response = $this
        ->actingAs($this->user)
        ->get(route('reservations.emailPreview', [
            'reservation' => $reservation
        ]))
        ->assertInertia(fn(Assert $page) => $page
            ->component('Reservations/EmailPreview')
            ->has('reservation', fn(Assert $page) => $page
                ->where('included_fees', "Kilometraje: 3k_kms, Seguro básico",)
                ->etc()
            )
        );
    }

    #[Group("preview_client_mail")]
    #[Test]
    public function check_data_if_included_fees_show_no_monthly_mileage_and_basic_insurance(): void
    {
        $category = Category::factory()->hasModels(2)->create([
            'name'  => 'Gama C',
            'category'  => 'Sedán automático',
            'description'  => '3 puertas',
        ]);

        $city = City::factory()->create([
            'name'  => "Cali",
        ]);

        $reservation = Reservation::factory()->create([
            'category' => $category->id,
            'selected_days'   =>  10,
            'total_insurance' => false,
        ]);

        $response = $this
        ->actingAs($this->user)
        ->get(route('reservations.emailPreview', [
            'reservation' => $reservation
        ]))
        ->assertInertia(fn(Assert $page) => $page
            ->component('Reservations/EmailPreview')
            ->has('reservation', fn(Assert $page) => $page
                ->where('included_fees', "Kilometraje ilimitado, Seguro básico",)
                ->etc()
            )
        );
    }

    #[Group("preview_client_mail")]
    #[Test]
    public function check_data_if_included_fees_show_no_monthly_mileage_and_total_insurance(): void
    {
        $category = Category::factory()->hasModels(2)->create([
            'name'  => 'Gama C',
            'category'  => 'Sedán automático',
            'description'  => '3 puertas',
        ]);

        $city = City::factory()->create([
            'name'  => "Cali",
        ]);

        $reservation = Reservation::factory()->create([
            'category' => $category->id,
            'selected_days'   =>  10,
            'total_insurance' => true,
        ]);

        $response = $this
        ->actingAs($this->user)
        ->get(route('reservations.emailPreview', [
            'reservation' => $reservation
        ]))
        ->assertInertia(fn(Assert $page) => $page
            ->component('Reservations/EmailPreview')
            ->has('reservation', fn(Assert $page) => $page
                ->where('included_fees', "Kilometraje ilimitado, Seguro total",)
                ->etc()
            )
        );
    }
}
