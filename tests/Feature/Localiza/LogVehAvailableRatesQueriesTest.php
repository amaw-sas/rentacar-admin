<?php

namespace Tests\Feature;

use App\Jobs\LogVehAvailableRatesQueryJob;
use App\Models\LogVehAvailableRatesQuery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Bus;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LogVehAvailableRatesQueriesTest extends TestCase
{
    use RefreshDatabase;

    public $defaultPayload = [
        'pickupLocation' => 'AABOT',
        'returnLocation' => 'AABOT',
        'pickupDateTime' => "2024-01-15T23:00:00",
        'returnDateTime' => "2024-01-17T23:00:00",
    ];

    #[Group("log_veh_available_rates_queries")]
    #[Group("localiza")]
    #[Test]
    public function log_a_regular_veh_avail_rate_query_and_trigger_job(): void {

        $xml = (View::make('localiza.tests.responses.vehavailrate.vehavailrate-xml'))->render();

        Bus::fake();

        $response = $this->post(route('dummy.disponibilidad'), $this->defaultPayload);

        $response
        ->assertStatus(200);

        Bus::assertDispatched(LogVehAvailableRatesQueryJob::class);

    }

    #[Group("log_veh_available_rates_queries")]
    #[Group("localiza")]
    #[Test]
    public function log_a_success_query_and_have_a_row_in_table(): void {
        $xml = (View::make('localiza.tests.responses.vehavailrate.vehavailrate-xml'))->render();

        $response = $this->post(route('dummy.disponibilidad'), $this->defaultPayload);

        $response
        ->assertStatus(200);


        $logItem = LogVehAvailableRatesQuery::first();
        $this->assertNotNull($logItem);
        $this->assertEquals($logItem->response_status, 200);
        $this->assertEquals($logItem->response_raw, $xml);

        $response->assertJson(json_decode($logItem->processed_data, true));
    }

    #[Group("log_veh_available_rates_queries")]
    #[Group("localiza")]
    #[Test]
    public function log_a_failed_query_not_categories_available_and_have_a_row_in_table(): void {
        $xml = (View::make('localiza.tests.responses.vehavailrate.vehavailrate-no-available-categories-xml'))->render();

        $response = $this->post(route('dummy.sin_disponibilidad'), $this->defaultPayload);

        $response
        ->assertStatus(500);


        $logItem = LogVehAvailableRatesQuery::first();
        $this->assertNotNull($logItem);
        $this->assertEquals($logItem->response_status, 200);
        $this->assertEquals($logItem->response_raw, $xml);

        $response->assertJson(json_decode($logItem->processed_data, true));
    }

    #[Group("log_veh_available_rates_queries")]
    #[Group("localiza")]
    #[Test]
    public function log_a_failed_query_out_of_schedule_pickup_hour_and_have_a_row_in_table(): void {
        $xml = (View::make('localiza.tests.responses.vehavailrate.vehavailrate-out-schedule-pickup-hour-xml'))->render();

        $response = $this->post(route('dummy.hora_recogida_fuera_horario'), $this->defaultPayload);

        $response
        ->assertStatus(500);


        $logItem = LogVehAvailableRatesQuery::first();
        $this->assertNotNull($logItem);
        $this->assertEquals($logItem->response_status, 200);
        $this->assertEquals($logItem->response_raw, $xml);

        $response->assertJson(json_decode($logItem->processed_data, true));
    }

    #[Group("log_veh_available_rates_queries")]
    #[Group("localiza")]
    #[Test]
    public function log_a_failed_query_out_of_schedule_return_hour_and_have_a_row_in_table(): void {
        $xml = (View::make('localiza.tests.responses.vehavailrate.vehavailrate-out-schedule-return-hour-xml'))->render();

        $response = $this->post(route('dummy.hora_devolucion_fuera_horario'), $this->defaultPayload);

        $response
        ->assertStatus(500);


        $logItem = LogVehAvailableRatesQuery::first();
        $this->assertNotNull($logItem);
        $this->assertEquals($logItem->response_status, 200);
        $this->assertEquals($logItem->response_raw, $xml);

        $response->assertJson(json_decode($logItem->processed_data, true));
    }

    #[Group("log_veh_available_rates_queries")]
    #[Group("localiza")]
    #[Test]
    public function log_a_failed_query_out_of_schedule_pickup_date_and_have_a_row_in_table(): void {
        $xml = (View::make('localiza.tests.responses.vehavailrate.vehavailrate-out-schedule-pickup-date-xml'))->render();

        $response = $this->post(route('dummy.fecha_recogida_fuera_horario'), $this->defaultPayload);

        $response
        ->assertStatus(500);


        $logItem = LogVehAvailableRatesQuery::first();
        $this->assertNotNull($logItem);
        $this->assertEquals($logItem->response_status, 200);
        $this->assertEquals($logItem->response_raw, $xml);

        $response->assertJson(json_decode($logItem->processed_data, true));
    }

    #[Group("log_veh_available_rates_queries")]
    #[Group("localiza")]
    #[Test]
    public function log_a_failed_query_out_of_schedule_return_date_and_have_a_row_in_table(): void {
        $xml = (View::make('localiza.tests.responses.vehavailrate.vehavailrate-out-schedule-return-date-xml'))->render();

        $response = $this->post(route('dummy.fecha_devolucion_fuera_horario'), $this->defaultPayload);

        $response
        ->assertStatus(500);


        $logItem = LogVehAvailableRatesQuery::first();
        $this->assertNotNull($logItem);
        $this->assertEquals($logItem->response_status, 200);
        $this->assertEquals($logItem->response_raw, $xml);

        $response->assertJson(json_decode($logItem->processed_data, true));
    }

    #[Group("log_veh_available_rates_queries")]
    #[Group("localiza")]
    #[Test]
    public function log_a_failed_timeout_query_and_have_a_row_in_table(): void {
        $xml = (View::make('localiza.tests.responses.vehavailrate.vehavailrate-xml'))->render();

        $response = $this->post(route('dummy.timeout'), $this->defaultPayload);

        $response
        ->assertStatus(500);

        $logItem = LogVehAvailableRatesQuery::first();
        $this->assertNotNull($logItem);
        $this->assertEquals($logItem->response_status, 408);
        $this->assertEquals($logItem->response_raw, null);

        $response->assertJson(json_decode($logItem->processed_data, true));
    }

    #[Group("log_veh_available_rates_queries")]
    #[Group("localiza")]
    #[Group("bug")]
    #[Test]
    public function when_log_a_event_it_doesnt_record_timestamps(): void {
        $xml = (View::make('localiza.tests.responses.vehavailrate.vehavailrate-xml'))->render();

        $response = $this->post(route('dummy.disponibilidad'), $this->defaultPayload);

        $response
        ->assertStatus(200);


        $logItem = LogVehAvailableRatesQuery::first();
        $this->assertNotNull($logItem);
        $this->assertEquals($logItem->response_status, 200);
        $this->assertEquals($logItem->response_raw, $xml);
        $this->assertNotNull($logItem->created_at);
        $this->assertNotNull($logItem->updated_at);

        $response->assertJson(json_decode($logItem->processed_data, true));
    }

    #[Group("log_veh_available_rates_queries")]
    #[Group("localiza")]
    #[Group("prunning")]
    #[Test]
    public function when_a_log_past_three_month_prune_it(): void {
        LogVehAvailableRatesQuery::factory()->create([
            'created_at'    => now()->subMonths(4)->format("Y-m-d")
        ]);
        $this->assertNotNull(LogVehAvailableRatesQuery::first());
        $this->artisan('model:prune');
        $this->assertNull(LogVehAvailableRatesQuery::first());

        LogVehAvailableRatesQuery::factory()->create([
            'created_at'    => now()->subMonths(2)->format("Y-m-d")
        ]);
        $this->assertNotNull(LogVehAvailableRatesQuery::first());
        $this->artisan('model:prune');
        $this->assertNotNull(LogVehAvailableRatesQuery::first());

    }

}
