<?php

namespace App\Http\Middleware;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Franchise;
use App\Enums\IdentificationType;
use App\Enums\ReservationStatus;
use App\Enums\MonthlyMileage;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'identification_types'  => fn() => array_map(
                fn($type) => ['text' => $type->value, 'value' => $type->value],
                IdentificationType::cases()
            ),
            'reservation_status' => fn() => [
                [
                    'text' => '1. Pendiente',
                    'value' => ReservationStatus::Pendiente->value
                ],
                [
                    'text' => '2. Reservado',
                    'value' => ReservationStatus::Reservado->value,
                ],
                [
                    'text' => '3. Sin Disponibilidad',
                    'value' => ReservationStatus::SinDisponibilidad->value,
                ],
                [
                    'text' => '4. Utilizado',
                    'value' => ReservationStatus::Utilizado->value,
                ],
                [
                    'text' => '5. No Contactado',
                    'value' => ReservationStatus::NoContactado->value,
                ],
                [
                    'text' => '5a. Baneado',
                    'value' => ReservationStatus::Baneado->value,
                ],
                [
                    'text' => '6. No Recogido',
                    'value' => ReservationStatus::NoRecogido->value,
                ],
                [
                    'text' => '7. Pendiente Pago',
                    'value' => ReservationStatus::PendientePago->value,
                ],
                [
                    'text' => '8. Pendiente x Modificar',
                    'value' => ReservationStatus::PendienteModificar->value,
                ],
                [
                    'text' => '9. Cancelado',
                    'value' => ReservationStatus::Cancelado->value,
                ],
                [
                    'text' => '10. Indeterminado',
                    'value' => ReservationStatus::Indeterminado->value,
                ],
            ],
            'monthly_mileages'  => fn() => array_map(
                fn($type) => ['text' => $type->value, 'value' => $type->value],
                MonthlyMileage::cases()
            ),
            'categories'    =>  fn() => Category::all(),
            'branches'    =>  fn() => Branch::orderBy('name','asc')->get()->all(),
            'franchises'    =>  fn() => Franchise::all(),
        ]);
    }
}
