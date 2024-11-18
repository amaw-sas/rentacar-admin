<?php

namespace Tests\Feature;

use App\Enums\MonthlyMileage;
use App\Enums\ReservationStatus;
use App\Models\Branch;
use App\Models\Franchise;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;

use App\Models\User;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        session()->flush();

    }

    #[Group("reservation")]
    #[Test]
    public function list_reservations(){
        Reservation::factory()->count(5)->create();

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index'))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',5)
        );
    }

    #[Group("reservation")]
    #[Test]
    public function filter_reservations_by_fullname(){
        $search = 'testing';
        Reservation::factory()->count(5)->create();
        $reservation = Reservation::factory()->create([
            'fullname'  => $search
        ]);

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index', [
                'query'    =>  $search
            ]))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',1)

                ->has('paginator.data.items.0', fn(Assert $page) => $page
                    ->where('fullname',$search)
                    ->etc()
                )
        );
    }

    #[Group("reservation")]
    #[Test]
    public function filter_reservations_by_identification(){
        $search = 'testing';
        Reservation::factory()->count(5)->create();
        $reservation = Reservation::factory()->create([
            'identification'  => $search
        ]);

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index', [
                'query'    =>  $search
            ]))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',1)
                ->has('paginator.data.items.0', fn(Assert $page) => $page
                    ->where('identification',$search)
                    ->etc()
                )
        );
    }

    #[Group("reservation")]
    #[Test]
    public function filter_reservations_by_phone(){
        $search = '+3155555555';
        Reservation::factory()->count(5)->create();
        $reservation = Reservation::factory()->create([
            'phone'  => $search
        ]);

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index', [
                'query'    =>  $search
            ]))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',1)
                ->has('paginator.data.items.0', fn(Assert $page) => $page
                    ->where('phone',$search)
                    ->etc()
                )
        );
    }

    #[Group("reservation")]
    #[Test]
    public function filter_reservations_by_email(){
        $search = 'test@test.com';
        Reservation::factory()->count(5)->create();
        $reservation = Reservation::factory()->create([
            'email'  => $search
        ]);

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index', [
                'query'    =>  $search
            ]))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',1)
                ->has('paginator.data.items.0', fn(Assert $page) => $page
                    ->where('email',$search)
                    ->etc()
                )
        );
    }

    #[Group("reservation")]
    #[Test]
    public function filter_reservations_by_reserve_code(){
        $search = '012345';
        Reservation::factory()->count(5)->create();
        $reservation = Reservation::factory()->create([
            'reserve_code'  => $search
        ]);

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index', [
                'query'    =>  $search
            ]))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',1)

                ->has('paginator.data.items.0', fn(Assert $page) => $page
                    ->where('reserve_code',$search)
                    ->etc()
                )
        );
    }

    #[Group("reservation")]
    #[Test]
    public function filter_reservations_by_status(){
        $search = (ReservationStatus::Reservado)->value;
        Reservation::factory()->count(5)->create([
            'status'    =>  (ReservationStatus::Pendiente)->value
        ]);
        $reservation = Reservation::factory()->create([
            'status'  => $search
        ]);

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index', [
                'filterCols'    =>  [
                    'status'    =>  [
                        'value' =>  $search
                    ]
                ]
            ]))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',1)

                ->has('paginator.data.items.0', fn(Assert $page) => $page
                    ->where('status',$search)
                    ->etc()
                )
        );
    }

    #[Group("reservation")]
    #[Test]
    public function filter_reservations_by_franchise(){
        $search = Franchise::factory()->create();
        Reservation::factory()->count(5)->create();
        $reservation = Reservation::factory()->create([
            'franchise'  => $search
        ]);

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index', [
                'filterCols'    =>  [
                    'franchise'    =>  [
                        'value' =>  $search->id
                    ]
                ]
            ]))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',1)

                ->has('paginator.data.items.0', fn(Assert $page) => $page
                    ->where('franchise',$search->name)
                    ->etc()
                )
        );
    }

    #[Group("reservation")]
    #[Test]
    public function filter_reservations_by_pickup_date(){

        Reservation::factory()->count(5)->create([
            'pickup_date'   => now()->subMonth()->format('Y-m-d')
        ]);
        $reservation = Reservation::factory()->create([
            'pickup_date'  => now()->format('Y-m-d')
        ]);

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index', [
                'filterDateRanges'    =>  [
                    'pickup_date' => [
                        'start' => now()->subDays(2)->format('Y-m-d'),
                        'end' => now()->addDays(2)->format('Y-m-d'),
                    ]
                ]

            ]))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',1)
                ->has('paginator.data.items.0', fn(Assert $page) => $page
                    ->where('pickup_date',now()->locale('es')->isoFormat('LL'))
                    ->etc()
                )
        );
    }

    #[Group("reservation")]
    #[Test]
    public function filter_reservations_by_created_at(){

        Reservation::factory()->count(5)->create([
            'created_at'   => now()->subMonth()
        ]);
        $reservation = Reservation::factory()->create([
            'created_at'  => now()
        ]);

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index', [
                'filterDateRanges'    =>  [
                    'created_at' => [
                        'start' => now()->subDays(2)->format('Y-m-d'),
                        'end' => now()->addDays(2)->format('Y-m-d'),
                    ]
                ]

            ]))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',1)
                ->has('paginator.data.items.0', fn(Assert $page) => $page
                    ->where('created_at',now()->format('Y-m-d H:i a'))
                    ->etc()
                )
        );
    }

    #[Group("reservation")]
    #[Group("bug")]
    #[Test]
    public function filter_reservations_by_pickup_date_and_query_get_error(){

        Reservation::factory()->count(5)->create([
            'pickup_date'   => now()->subMonth()->format('Y-m-d')
        ]);
        $reservation = Reservation::factory()->create([
            'pickup_date'  => now()->format('Y-m-d'),
            'fullname'  => 'testing',
        ]);

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index', [
                'filterDateRanges'    =>  [
                    'pickup_date' => [
                        'start' => now()->subDays(2)->format('Y-m-d'),
                        'end' => now()->addDays(2)->format('Y-m-d'),
                    ],
                ],
                'query' =>  'testing'

            ]))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',1)
                ->has('paginator.data.items.0', fn(Assert $page) => $page
                    ->where('pickup_date',now()->locale('es')->isoFormat('LL'))
                    ->where('fullname', 'testing')
                    ->etc()
                )
        );
    }

    #[Group("reservation")]
    #[Test]
    public function filter_reservations_and_keep_filters_until_clean_filters(){
        $search = 'testing';
        Reservation::factory()->count(5)->create();
        $reservation = Reservation::factory()->create([
            'fullname'  => $search
        ]);

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index', [
                'query'    =>  $search
            ]))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',1)
                ->has('paginator.data.items.0', fn(Assert $page) => $page
                    ->where('fullname',$search)
                    ->etc()
                )
        );

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index'))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',1)
                ->has('paginator.data.items.0', fn(Assert $page) => $page
                    ->where('fullname',$search)
                    ->etc()
                )
        );
    }

    #[Group("reservation")]
    #[Test]
    public function filter_reservations_by_search_and_pickup_date_and_status_fields(){
        $status = (ReservationStatus::Reservado)->value;
        $name = 'testing';

        Reservation::factory()->count(5)->create([
            'status'    =>  (ReservationStatus::Pendiente)->value,
            'pickup_date'   => now()->subMonth()->format('Y-m-d'),
        ]);
        $reservation = Reservation::factory()->create([
            'status'  => $status,
            'pickup_date'  => now()->format('Y-m-d'),
            'fullname'  =>  $name
        ]);

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index', [
                'filterCols'    =>  [
                    'status'    =>  [
                        'value' =>  $status
                    ]
                ],
                'filterDateRanges'    =>  [
                    'pickup_date' => [
                        'start' => now()->subDays(2)->format('Y-m-d'),
                        'end' => now()->addDays(2)->format('Y-m-d'),
                    ]
                ],
                'query' =>  $name,
            ]))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',1)

                ->has('paginator.data.items.0', fn(Assert $page) => $page
                    ->where('status',$status)
                    ->where('fullname',$name)
                    ->where('pickup_date',now()->locale('es')->isoFormat('LL'))
                    ->etc()
                )
        );
    }

    #[Group("reservation")]
    #[Test]
    public function filter_reservations_by_search_and_pickup_date_and_status_and_franchise_fields(){
        $status = (ReservationStatus::Reservado)->value;
        $franchise = Franchise::factory()->create();
        $name = 'testing';

        Reservation::factory()->count(5)->create([
            'status'    =>  (ReservationStatus::Pendiente)->value,
            'pickup_date'   => now()->subMonth()->format('Y-m-d'),
        ]);
        $reservation = Reservation::factory()->create([
            'status'  => $status,
            'pickup_date'  => now()->format('Y-m-d'),
            'fullname'  =>  $name,
            'franchise' =>  $franchise
        ]);

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index', [
                'filterCols'    =>  [
                    'status'    =>  [
                        'value' =>  $status
                    ],
                    'franchise'    =>  [
                        'value' =>  $franchise->id
                    ],
                ],
                'filterDateRanges'    =>  [
                    'pickup_date' => [
                        'start' => now()->subDays(2)->format('Y-m-d'),
                        'end' => now()->addDays(2)->format('Y-m-d'),
                    ]
                ],
                'query' =>  $name,
            ]))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',1)

                ->has('paginator.data.items.0', fn(Assert $page) => $page
                    ->where('status',$status)
                    ->where('fullname',$name)
                    ->where('franchise',$franchise->name)
                    ->where('pickup_date',now()->locale('es')->isoFormat('LL'))
                    ->etc()
                )
        );
    }

    #[Group("reservation")]
    #[Test]
    public function filter_reservations_by_search_and_pickup_date_and_created_at_and_status_and_franchise_fields(){
        $status = (ReservationStatus::Reservado)->value;
        $franchise = Franchise::factory()->create();
        $name = 'testing';

        Reservation::factory()->count(5)->create([
            'status'    =>  (ReservationStatus::Pendiente)->value,
            'pickup_date'   => now()->subMonth()->format('Y-m-d'),
            'created_at'   => now()->subMonth(),
        ]);
        $reservation = Reservation::factory()->create([
            'status'  => $status,
            'pickup_date'  => now()->format('Y-m-d'),
            'created_at'  => now(),
            'fullname'  =>  $name,
            'franchise' =>  $franchise
        ]);

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index', [
                'filterCols'    =>  [
                    'status'    =>  [
                        'value' =>  $status
                    ],
                    'franchise'    =>  [
                        'value' =>  $franchise->id
                    ],
                ],
                'filterDateRanges'    =>  [
                    'pickup_date' => [
                        'start' => now()->subDays(2)->format('Y-m-d'),
                        'end' => now()->addDays(2)->format('Y-m-d'),
                    ],
                    'created_at' => [
                        'start' => now()->subDays(2)->format('Y-m-d'),
                        'end' => now()->addDays(2)->format('Y-m-d'),
                    ],
                ],
                'query' =>  $name,
            ]))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',1)

                ->has('paginator.data.items.0', fn(Assert $page) => $page
                    ->where('status',$status)
                    ->where('fullname',$name)
                    ->where('franchise',$franchise->name)
                    ->where('pickup_date',now()->locale('es')->isoFormat('LL'))
                    ->where('created_at',now()->format('Y-m-d H:i a'))
                    ->etc()
                )
        );
    }

    #[Group("reservation")]
    #[Test]
    public function clean_filters(){
        $search = 'testing';
        Reservation::factory()->count(5)->create();
        $reservation = Reservation::factory()->create([
            'fullname'  => $search
        ]);

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index', [
                'query'    =>  $search
            ]))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',1)
                ->has('paginator.data.items.0', fn(Assert $page) => $page
                    ->where('fullname',$search)
                    ->etc()
                )
        );

        $this
            ->actingAs($this->user)
            ->get(route('reservations.cleanFilters'));

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index'))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',6)
        );
    }

    #[Group("reservation")]
    #[Test]
    public function paginate_reservations(){
        $search = 'testing';
        $reservations = Reservation::factory()
            ->recycle(Branch::factory()->create())
            ->count(15)
            ->create([
                'created_at'    => now()
            ]);
        $reservation = Reservation::factory()
            ->create([
                'fullname'  => $search,
                'created_at'    => now()->subDay()
            ]);

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index'), [
                'orderByCol'    => 'created_at',
                'orderOrientation'    => 'desc',
                'page'      =>  1
            ])
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',15)

        );

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index', [
                'orderByCol'    => 'created_at',
                'orderOrientation'    => 'desc',
                'page'      =>  2
            ]))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',1)
                ->has('paginator.data.items.0', fn(Assert $page) => $page
                    ->where('fullname',$search)
                    ->etc()
                )
        );

    }

    #[Group("reservation")]
    #[Test]
    public function allow_keep_the_page_when_paginate_and_go_to_other_page(){
        $search = 'testing';
        $reservations = Reservation::factory()
            ->recycle(Branch::factory()->create())
            ->count(15)
            ->create([
                'created_at'    => now()
            ]);
        $reservation = Reservation::factory()
            ->create([
                'fullname'  => $search,
                'created_at'    => now()->subDay()
            ]);

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index'), [
                'orderByCol'    => 'created_at',
                'orderOrientation'    => 'desc',
                'page'      =>  1
            ])
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',15)

        );

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index', [
                'orderByCol'    => 'created_at',
                'orderOrientation'    => 'desc',
                'page'      =>  2
            ]))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',1)
                ->has('paginator.data.items.0', fn(Assert $page) => $page
                    ->where('fullname',$search)
                    ->etc()
                )
        );

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index'))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',1)
                ->has('paginator.data.items.0', fn(Assert $page) => $page
                    ->where('fullname',$search)
                    ->etc()
                )
        );

    }

    #[Group("reservation")]
    #[Test]
    public function keep_filters_when_edit_a_reservation(){
        $search = 'testing';
        $reservations = Reservation::factory()
            ->recycle(Branch::factory()->create())
            ->count(15)
            ->create([
                'created_at'    => now()
            ]);
        $reservation = Reservation::factory()
            ->create([
                'fullname'  => $search,
                'created_at'    => now()->subDay()
            ]);

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index'), [
                'orderByCol'    => 'created_at',
                'orderOrientation'    => 'desc',
                'page'      =>  1
            ])
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',15)

        );

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index', [
                'orderByCol'    => 'created_at',
                'orderOrientation'    => 'desc',
                'page'      =>  2
            ]))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',1)
                ->has('paginator.data.items.0', fn(Assert $page) => $page
                    ->where('fullname',$search)
                    ->etc()
                )
        );

        // edit a reservation
        $reservationData = $reservation->toArray();
        $newIdentification = "1111111";
        $reservationData['identification'] = $newIdentification;

        $this
            ->actingAs($this->user)
            ->putJson(route('reservations.update',[
                'reservation' => $reservation->id
            ]), $reservationData)
            ->assertStatus(302);

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index'))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',1)
                ->has('paginator.data.items.0', fn(Assert $page) => $page
                    ->where('fullname',$search)
                    ->where('identification',$newIdentification)
                    ->etc()
                )
        );

    }


    #[Group("reservation")]
    #[Test]
    public function create_a_reservation(){
        $reservationData = Reservation::factory()->make()->toArray();

        $response = $this
            ->actingAs($this->user)
            ->postJson(route('reservations.store'), $reservationData);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals($reservationData['fullname'], $reservation->fullname);
    }

    #[Group("reservation")]
    #[Test]
    public function when_create_a_post_and_no_data_show_error_messages(){
        $reservationData = [];

        $response = $this
            ->actingAs($this->user)
            ->postJson(route('reservations.store'), $reservationData);

        $response
            ->assertJson(fn (AssertableJson $json) =>
                $json
                    ->where('errors.fullname.0', "El campo Nombre completo es requerido.")
                    ->where('errors.identification_type.0', "El campo Tipo de identificación es requerido.")
                    ->where('errors.identification.0', "El campo Identificación es requerido.")
                    ->where('errors.phone.0', "El campo Teléfono es requerido.")
                    ->where('errors.email.0', "El campo Correo eléctronico es requerido.")
                    ->where('errors.category.0', "El campo Categoría es requerido.")
                    ->where('errors.pickup_location.0', "El campo Lugar de recogida es requerido.")
                    ->where('errors.return_location.0', "El campo Lugar de retorno es requerido.")
                    ->where('errors.pickup_date.0', "El campo Día de recogida es requerido.")
                    ->where('errors.return_date.0', "El campo Día de retorno es requerido.")
                    ->where('errors.pickup_hour.0', "El campo Hora de recogida es requerido.")
                    ->where('errors.return_hour.0', "El campo Hora de retorno es requerido.")
                    ->where('errors.selected_days.0', "El campo Días reservados es requerido.")
                    ->where('errors.coverage_days.0', "El campo Días seguro es requerido.")
                    ->where('errors.coverage_price.0', "El campo Precio seguro es requerido.")
                    ->where('errors.tax_fee.0', "El campo Tasa administrativa es requerido.")
                    ->where('errors.iva_fee.0', "El campo Precio IVA es requerido.")
                    ->where('errors.total_price.0', "El campo Precio sin iva con tasa es requerido.")
                    ->where('errors.total_price_to_pay.0', "El campo Precio total a pagar es requerido.")
                    ->where('errors.franchise.0', "El campo Franquicia es requerido.")
                    ->where('errors.status.0', "El campo Estado es requerido.")

                    ->etc()
        );
    }

    #[Group("reservation")]
    #[Test]
    public function update_a_reservation(){
        $reservation = Reservation::factory()->create();
        $reservationData = $reservation->toArray();
        $reservationData['fullname'] = 'testing';
        $reservationData['reserve_code'] = '012345';

        $response = $this
            ->actingAs($this->user)
            ->putJson(route('reservations.update', [
                'reservation'   =>  $reservation->id,
            ]), $reservationData);

        $reservation->refresh();
        $this->assertEquals('testing', $reservation->fullname);
        $this->assertEquals('012345', $reservation->reserve_code);
    }

    #[Group("reservation")]
    #[Test]
    public function delete_a_reservation(){
        $reservation = Reservation::factory()->create();

        $response = $this
            ->actingAs($this->user)
            ->deleteJson(route('reservations.destroy', [
                'reservation'   =>  $reservation->id,
            ]));

        $reservation = Reservation::first();
        $this->assertNull($reservation);
    }

    #[Group("reservation")]
    #[Group("bug")]
    #[Test]
    public function when_theres_category_null_reservations_list_not_show(){
        Reservation::factory()->count(5)->create();
        Reservation::factory()->create([
            'category'  =>  null
        ]);

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index'))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',6)
        );
    }

    #[Group("reservation")]
    #[Group("bug")]
    #[Test]
    public function when_theres_pickup_location_null_reservations_list_not_show(){
        Reservation::factory()->count(5)->create();
        Reservation::factory()->create([
            'pickup_location'  =>  null
        ]);

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index'))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',6)
        );
    }

    #[Group("reservation")]
    #[Group("bug")]
    #[Test]
    public function when_theres_return_location_null_reservations_list_not_show(){
        Reservation::factory()->count(5)->create();
        Reservation::factory()->create([
            'return_location'  =>  null
        ]);

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index'))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',6)
        );
    }

    #[Group("reservation")]
    #[Group("bug")]
    #[Test]
    public function when_theres_franchise_null_reservations_list_not_show(){
        Reservation::factory()->count(5)->create();
        Reservation::factory()->create([
            'franchise'  =>  null
        ]);

        $this
            ->actingAs($this->user)
            ->get(route('reservations.index'))
            ->assertInertia(fn(Assert $page) => $page
                ->component('Reservations/Index')
                ->has('paginator.data.items',6)
        );
    }

    #[Group("reservation")]
    #[Group("monthly_mileage")]
    #[Test]
    public function create_a_reservation_with_monthly_mileage(){
        $reservationData = Reservation::factory()->make()->toArray();
        $reservationData['monthly_mileage'] = MonthlyMileage::twoKKms->value;

        $response = $this
            ->actingAs($this->user)
            ->postJson(route('reservations.store'), $reservationData);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals($reservationData['fullname'], $reservation->fullname);
        $this->assertEquals($reservationData['monthly_mileage'], $reservation->monthly_mileage);
    }

    #[Group("reservation")]
    #[Group("monthly_mileage")]
    #[Test]
    public function create_a_reservation_with_empty_monthly_mileage(){
        $reservationData = Reservation::factory()->make()->toArray();
        $reservationData['monthly_mileage'] = null;

        $response = $this
            ->actingAs($this->user)
            ->postJson(route('reservations.store'), $reservationData);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals($reservationData['fullname'], $reservation->fullname);
        $this->assertEquals($reservationData['monthly_mileage'], $reservation->monthly_mileage);
    }

    #[Group("reservation")]
    #[Group("total_insurance")]
    #[Test]
    public function create_a_reservation_with_total_insurance(){
        $reservationData = Reservation::factory()->make()->toArray();
        $reservationData['total_insurance'] = true;

        $response = $this
            ->actingAs($this->user)
            ->postJson(route('reservations.store'), $reservationData);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals($reservationData['fullname'], $reservation->fullname);
        $this->assertTrue((bool) $reservation->total_insurance);
    }

    #[Group("reservation")]
    #[Group("total_insurance")]
    #[Test]
    public function create_a_reservation_with_no_total_insurance(){
        $reservationData = Reservation::factory()->make()->toArray();
        $reservationData['total_insurance'] = false;

        $response = $this
            ->actingAs($this->user)
            ->postJson(route('reservations.store'), $reservationData);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals($reservationData['fullname'], $reservation->fullname);
        $this->assertFalse((bool) $reservation->total_insurance);
    }

    #[Group("reservation")]
    #[Group("return_fee")]
    #[Test]
    public function create_a_reservation_with_return_fee(){
        $reservationData = Reservation::factory()->make()->toArray();
        $reservationData['return_fee'] = 1000;

        $response = $this
            ->actingAs($this->user)
            ->postJson(route('reservations.store'), $reservationData);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals($reservationData['fullname'], $reservation->fullname);
        $this->assertEquals($reservationData['return_fee'], $reservation->return_fee);
    }

    #[Group("reservation")]
    #[Group("return_fee")]
    #[Test]
    public function create_a_reservation_with_null_return_fee_and_return_0(){
        $reservationData = Reservation::factory()->make()->toArray();
        $reservationData['return_fee'] = null;

        $response = $this
            ->actingAs($this->user)
            ->postJson(route('reservations.store'), $reservationData);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals($reservationData['fullname'], $reservation->fullname);
        $this->assertEquals(0, $reservation->return_fee);
    }

    #[Group("reservation")]
    #[Group("return_fee")]
    #[Test]
    public function create_a_reservation_with_0_return_fee_and_return_0(){
        $reservationData = Reservation::factory()->make()->toArray();
        $reservationData['return_fee'] = 0;

        $response = $this
            ->actingAs($this->user)
            ->postJson(route('reservations.store'), $reservationData);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);
        $this->assertEquals($reservationData['fullname'], $reservation->fullname);
        $this->assertEquals(0, $reservation->return_fee);
    }

    #[Group("reservation")]
    #[Group("pickup_date_time")]
    #[Test]
    public function get_reservation_pickup_date_time(){
        $reservation = Reservation::factory()->create([
            'pickup_date'   =>  '2028-01-01',
            'pickup_hour'   =>  '08:00',
        ]);


        $this->assertEquals('2028-01-01T08:00:00', $reservation->getPickupDateTime());
    }

    #[Group("reservation")]
    #[Group("return_date_time")]
    #[Test]
    public function get_reservation_return_date_time(){
        $reservation = Reservation::factory()->create([
            'return_date'   =>  '2028-01-01',
            'return_hour'   =>  '08:00',
        ]);


        $this->assertEquals('2028-01-01T08:00:00', $reservation->getReturnDateTime());
    }

}
