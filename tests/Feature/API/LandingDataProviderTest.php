<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Database\Eloquent\Factories\Sequence;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Str;
use Tests\TestCase;

use App\Models\Branch;
use App\Models\CityPage;
use App\Models\User;
use App\Models\Category;
use App\Models\CityFranchiseWhatsappLink;
use App\Models\CityCategoryVisibility;

class LandingDataProviderTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

    }

    #[Group("landing_data_provider")]
    #[Group("data_provider")]
    #[Test]
    public function send_without_url_parameter_and_return_a_not_found_exception() {

        $this->post(route('dataprovider.landing'))
            ->assertNotFound();
    }

    #[Group("landing_data_provider")]
    #[Group("data_provider")]
    #[Test]
    public function send_a_url_but_no_city_page_is_found() {

        $this->post(route('dataprovider.landing'), [
            'url'   =>  $this->faker->url()
        ])
        ->assertNotFound();
    }

    #[Group("landing_data_provider")]
    #[Group("data_provider")]
    #[Test]
    public function show_json_with_categories_pageconfig_and_branches_when_theres_a_city_page() {
        $city_page = CityPage::factory()->create();
        $city = Str::slug($city_page->city->name);
        $branches = Branch::factory()->count(10)->create();
        $categories = Category::factory()->count(10)->create();

        $this->post(route('dataprovider.landing'), [
            'url'   =>  $city_page->url
        ])
        ->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
            $json
                ->has('categories',10)
                ->has('branches',10)
                ->has('page_config')
                ->where('city', $city)
        );
    }

    #[Group("landing_data_provider")]
    #[Group("data_provider")]
    #[Test]
    public function show_a_category_data() {
        $city_page = CityPage::factory()->create();
        $city = $city_page->city;
        $categories = Category::factory()->count(3)->state(new Sequence(
            ['identification' => 'A'],
            ['identification' => 'C'],
            ['identification' => 'F'],
        ))->create();
        $branch = Branch::factory()->create([
            'city_id'   =>  $city->id
        ]);

        $category = $categories[0];

        $this->post(route('dataprovider.landing'), [
            'url'   =>  $city_page->url
        ])
        ->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
            $json
                ->has('categories.0', fn($json) =>
                    $json
                        ->where('identification', $category->identification)
                        ->where('name', $category->name)
                        ->where('category', $category->category)
                        ->where('description', $category->description)
                        ->where('image', asset("storage/{$category->image}"))
                        ->where('ad', $category->ad)
                        ->where('active', 1)
                        ->etc()
                )->etc()
        );

    }

    #[Group("landing_data_provider")]
    #[Group("data_provider")]
    #[Test]
    public function when_theres_a_per_category_matrix_price_show_that_price_in_base_price_attribute() {
        $city_page = CityPage::factory()->create();
        $city = $city_page->city;
        $categories = Category::factory()->count(3)->create();
        $branch = Branch::factory()->create([
            'city_id'   =>  $city->id
        ]);

        $category = $categories[0];

        $this->post(route('dataprovider.landing'), [
            'url'   =>  $city_page->url
        ])
        ->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
            $json
                ->has('categories.0', fn($json) =>
                    $json
                        ->where('identification', $category->identification)
                        ->where('name', $category->name)
                        ->where('category', $category->category)
                        ->where('description', $category->description)
                        ->where('image', asset("storage/{$category->image}"))
                        ->where('ad', $category->ad)
                        ->where('active', 1)
                        ->etc()
                )->etc()
        );

    }

    #[Group("landing_data_provider")]
    #[Group("data_provider")]
    #[Test]
    public function show_branch_data() {
        $city_page = CityPage::factory()->create();
        $branches = Branch::factory()->count(2)->create();
        $categories = Category::factory()->count(1)->create();
        $branch = $branches[0];

        $this->post(route('dataprovider.landing'), [
            'url'   =>  $city_page->url
        ])
        ->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
            $json
                ->has('branches',2)
                ->has('branches.0', fn($json) =>
                    $json
                        ->where('id', $branch->id)
                        ->where('code', $branch->code)
                        ->where('name', $branch->name)
                        ->etc()
                )
                ->etc()
        );
    }

    #[Group("landing_data_provider")]
    #[Group("data_provider")]
    #[Test]
    public function show_page_config() {
        $city_page = CityPage::factory()->create();
        $branches = Branch::factory()->count(2)->create();
        $categories = Category::factory()->count(1)->create();
        $franchise = $city_page->franchise;

        $this->post(route('dataprovider.landing'), [
            'url'   =>  $city_page->url
        ])
        ->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
            $json
                ->has('page_config', fn($json) =>
                    $json
                        ->where('boton_reserva', $franchise->reserva_button)
                        ->where('boton_masinfo', $franchise->masinfo_button)
                        ->where('boton_masprecios', $franchise->masprecios_button)
                        ->where('aviso', $franchise->ad)
                        ->where('carrusel', $franchise->carousel)
                        ->where('pagina_web', $franchise->url_mail_system)
                        ->etc()
                )
                ->etc()
        );
    }

    #[Group("landing_data_provider")]
    #[Group("data_provider")]
    #[Test]
    public function show_car_models_in_category_data() {
        $city_page = CityPage::factory()->create();
        $branch = Branch::factory()->create();
        $category = Category::factory()->hasModels(2)->create();
        $car_models = $category->models()->get();

        $this->post(route('dataprovider.landing'), [
            'url'   =>  $city_page->url
        ])
        ->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
            $json
                ->has('categories',1)
                ->has('categories.0.models',2)
                ->has('categories.0.models.0',fn($json) =>
                    $json
                    ->where('name', $car_models[0]->name)
                    ->where('description', $car_models[0]->description)
                    ->where('default', (boolean) $car_models[0]->default)
                    ->where('image', asset("storage/{$car_models[0]->image}"))
                    ->etc()
                )
                ->has('categories.0.models.1',fn($json) =>
                    $json
                    ->where('name', $car_models[1]->name)
                    ->where('description', $car_models[1]->description)
                    ->where('default', (boolean) $car_models[1]->default)
                    ->where('image', asset("storage/{$car_models[1]->image}"))
                    ->etc()
                )
                ->etc()
        );
    }

    #[Group("landing_data_provider")]
    #[Group("data_provider")]
    #[Test]
    public function show_car_month_prices_in_category_data() {
        $city_page = CityPage::factory()->create();
        $branch = Branch::factory()->create();
        $category = Category::factory()->hasMonthPrices(2)->create();
        $car_month_prices = $category->monthPrices()->get();

        $this->post(route('dataprovider.landing'), [
            'url'   =>  $city_page->url
        ])
        ->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
            $json
                ->has('categories',1)
                ->has('categories.0.month_prices',2)
                ->has('categories.0.month_prices.0',fn($json) =>
                    $json
                    ->where('1k_kms', $car_month_prices[0]->{'1k_kms'})
                    ->where('2k_kms', $car_month_prices[0]->{'2k_kms'})
                    ->where('3k_kms', $car_month_prices[0]->{'3k_kms'})
                    ->where('init_date', $car_month_prices[0]->init_date->format('d-m-Y'))
                    ->where('end_date', $car_month_prices[0]->end_date->format('d-m-Y'))
                )
                ->has('categories.0.month_prices.1',fn($json) =>
                    $json
                    ->where('1k_kms', $car_month_prices[1]->{'1k_kms'})
                    ->where('2k_kms', $car_month_prices[1]->{'2k_kms'})
                    ->where('3k_kms', $car_month_prices[1]->{'3k_kms'})
                    ->where('init_date', $car_month_prices[1]->init_date->format('d-m-Y'))
                    ->where('end_date', $car_month_prices[1]->end_date->format('d-m-Y'))
                )
                ->etc()
        );
    }

    #[Group("landing_data_provider")]
    #[Group("data_provider")]
    #[Test]
    public function get_whatspp_link_from_the_city(): void {

        $branch = Branch::factory()->create();
        $category = Category::factory()->hasModels(2)->create();
        $city_franchise_whatsapplink = CityFranchiseWhatsappLink::factory()->create();
        $city_page = CityPage::factory()->create([
            'city_id'   => $city_franchise_whatsapplink->city->id,
            'franchise_id'  =>  $city_franchise_whatsapplink->franchise->id,
        ]);

        $this->post(route('dataprovider.landing'), [
            'url'   =>  $city_page->url
        ])
        ->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
            $json
                ->has('page_config',fn($json) =>
                    $json
                    ->where('boton_masinfo', $city_franchise_whatsapplink->whatsapp_link)
                    ->etc()
                )
                ->etc()
        );

    }

    #[Group("landing_data_provider")]
    #[Group("data_provider")]
    #[Test]
    public function if_theres_no_city_whatsapp_link_associated_get_default_whatsapp_link_from_franchise(): void {

        $branch = Branch::factory()->create();
        $category = Category::factory()->hasModels(2)->create();
        $city_franchise_whatsapplink = CityFranchiseWhatsappLink::factory()->create();
        $city_page = CityPage::factory()->create();

        $this->post(route('dataprovider.landing'), [
            'url'   =>  $city_page->url
        ])
        ->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
            $json
                ->has('page_config',fn($json) =>
                    $json
                    ->where('boton_masinfo', $city_page->franchise->masinfo_button)
                    ->etc()
                )
                ->etc()
        );

    }

    #[Group("landing_data_provider")]
    #[Group("city_category_visibility")]
    #[Group("data_provider")]
    #[Test]
    public function if_the_city_category_visibility_is_false_return_the_category_status_as_false() {
        $branch = Branch::factory()->create();
        $category = Category::factory()->create();
        $city_franchise_whatsapplink = CityFranchiseWhatsappLink::factory()->create();
        $city_page = CityPage::factory()->create();
        $city = $city_page->city;
        $city_category_visibility = CityCategoryVisibility::factory()->create([
            'city_id'   =>  $city->id,
            'category_id'    =>  $category->id,
            'visible'   =>  false
        ]);

        $this->post(route('dataprovider.landing'), [
            'url'   =>  $city_page->url
        ])
        ->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
            $json
                ->where('categories.0.active',(integer) 0)
                ->etc()
        );

    }

    #[Group("landing_data_provider")]
    #[Group("city_category_visibility")]
    #[Group("data_provider")]
    #[Test]
    public function if_the_city_category_visibility_is_true_return_the_category_status_as_true() {
        $branch = Branch::factory()->create();
        $category = Category::factory()->create();
        $city_franchise_whatsapplink = CityFranchiseWhatsappLink::factory()->create();
        $city_page = CityPage::factory()->create();
        $city = $city_page->city;
        $city_category_visibility = CityCategoryVisibility::factory()->create([
            'city_id'   =>  $city->id,
            'category_id'    =>  $category->id,
            'visible'   =>  true
        ]);

        $this->post(route('dataprovider.landing'), [
            'url'   =>  $city_page->url
        ])
        ->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
            $json
                ->where('categories.0.active',(integer) 1)
                ->etc()
        );

    }

    #[Group("landing_data_provider")]
    #[Group("city_category_visibility")]
    #[Group("data_provider")]
    #[Test]
    public function if_theres_no_city_category_visibility_return_the_category_status_as_true() {
        $branch = Branch::factory()->create();
        $category = Category::factory()->create();
        $city_franchise_whatsapplink = CityFranchiseWhatsappLink::factory()->create();
        $city_page = CityPage::factory()->create();


        $this->post(route('dataprovider.landing'), [
            'url'   =>  $city_page->url
        ])
        ->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
            $json
                ->where('categories.0.active',(integer) 1)
                ->etc()
        );

    }

}
