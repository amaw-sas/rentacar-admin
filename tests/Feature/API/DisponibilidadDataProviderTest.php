<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use App\Models\Branch;

class DisponibilidadDataProviderTest extends TestCase {

    use WithFaker;
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();

        $xml = view('localiza.tests.responses.vehavailrate.vehavailrate-xml')->render();

        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);


    }

    #[Group("disponibilidad_data_provider")]
    #[Group("data_provider")]
    #[Test]
    public function get_data(){
        $pickupLocation = Branch::factory()->create();
        $returnLocation = Branch::factory()->create();
        $pickupDateTime = now()->format('Y-m-d H:a:s');
        $returnDateTime = now()->addDay()->format('Y-m-d H:a:s');

        $this->post(route('dataprovider.disponibilidad'), [
            'pickupLocation'   =>  $pickupLocation->code,
            'returnLocation'   =>  $returnLocation->code,
            'pickupDateTime'   =>  $pickupDateTime,
            'returnDateTime'   =>  $returnDateTime,
        ])
        ->assertOk();
    }
}
