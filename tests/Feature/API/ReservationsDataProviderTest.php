<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use App\Models\Branch;
use App\Models\CityPage;
use App\Models\User;
use App\Models\Category;

class ReservationsDataProviderTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

    }

    #[Group("reservations_data_provider")]
    #[Group("data_provider")]
    #[Test]
    public function send_without_franchise_parameter_and_return_a_not_found_exception() {

        $this->post(route('dataprovider.reservations'))
            ->assertNotFound();
    }

    #[Group("reservations_data_provider")]
    #[Group("data_provider")]
    #[Test]
    public function send_a_franchise_but_no_city_page_is_found() {

        $this->post(route('dataprovider.reservations'), [
            'franchise'   =>  $this->faker->word()
        ])
        ->assertNotFound();
    }

    #[Group("reservations_data_provider")]
    #[Group("data_provider")]
    #[Test]
    public function show_json_with_categories_pageconfig_and_branches_when_theres_a_city_page() {
        $city_page = CityPage::factory()->create();
        $branches = Branch::factory()->count(10)->create();
        $categories = Category::factory()->count(10)->create();

        $this->post(route('dataprovider.reservations'), [
            'franchise'   =>  $city_page->franchise->name
        ])
        ->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
            $json
                ->has('branches',10)
                ->has('page_config')
                ->etc()
        );
    }

    #[Group("reservations_data_provider")]
    #[Group("data_provider")]
    #[Test]
    public function show_category_data() {
        $city_page = CityPage::factory()->create();
        $branch = Branch::factory()->create();
        $category = Category::factory()->create([
            'identification'    =>  'C',
        ]);

        $this->post(route('dataprovider.reservations'), [
            'franchise'   =>  $city_page->franchise->name
        ])
        ->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
            $json
                ->has('categories',1)
                ->has('categories.0',fn($json) =>
                    $json
                    ->where('identification', $category->identification)
                    ->where('name', $category->name)
                    ->where('category', $category->category)
                    ->where('description', $category->description)
                    ->where('image', asset("storage/{$category->image}"))
                    ->where('ad', $category->ad)
                    ->etc()
                )->etc()
        );
    }

    #[Group("reservations_data_provider")]
    #[Group("data_provider")]
    #[Test]
    public function show_car_models_in_category_data() {
        $city_page = CityPage::factory()->create();
        $branch = Branch::factory()->create();
        $category = Category::factory()->hasModels(2)->create([
            'identification'    =>  'C',
        ]);
        $car_models = $category->models()->get();


        $this->post(route('dataprovider.reservations'), [
            'franchise'   =>  $city_page->franchise->name
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

    #[Group("reservations_data_provider")]
    #[Group("data_provider")]
    #[Test]
    public function show_branch_data() {
        $city_page = CityPage::factory()->create();
        $branches = Branch::factory()->count(2)->create();
        $categories = Category::factory()->count(1)->create();


        $this->post(route('dataprovider.reservations'), [
            'franchise'   =>  $city_page->franchise->name
        ])
        ->assertOk()
        ->assertJson(fn(AssertableJson $json) =>
            $json
                ->has('branches',2)
                ->has('branches.0', fn($json) =>
                    $json
                        ->where('id', $branches[0]->id)
                        ->where('name', $branches[0]->name)
                        ->where('code', $branches[0]->code)
                        ->where('city', Str::slug($branches[0]->city->name))
                        ->where('schedule', [])
                        ->etc()
                )
                ->has('branches.1', fn($json) =>
                    $json
                        ->where('id', $branches[1]->id)
                        ->where('name', $branches[1]->name)
                        ->where('code', $branches[1]->code)
                        ->where('city', Str::slug($branches[1]->city->name))
                        ->where('schedule', [])
                        ->etc()
                )
                ->etc()
        );
    }

    #[Group("reservations_data_provider")]
    #[Group("data_provider")]
    #[Test]
    public function show_page_config() {
        $city_page = CityPage::factory()->create();
        $branches = Branch::factory()->count(2)->create();
        $categories = Category::factory()->count(1)->create();
        $franchise = $city_page->franchise;

        $this->post(route('dataprovider.reservations'), [
            'franchise'   =>  $franchise->name
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


}
