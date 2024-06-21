<?php

namespace App\Http\Middleware;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Franchise;
use App\Enums\IdentificationType;
use App\Enums\ReservationStatus;
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
                    'value' => '1. Nueva',
                    'text' => ReservationStatus::Nueva->value
                ],
                [
                    'value' => '2. Con Código',
                    'text' => ReservationStatus::ConCodigo->value,
                ],
                [
                    'value' => '3. Sin Disponibilidad',
                    'text' => ReservationStatus::SinDisponibilidad->value,
                ],
                [
                    'value' => '4. Confirmado',
                    'text' => ReservationStatus::Confirmado->value,
                ],
                [
                    'value' => '5. Sin Confirmar',
                    'text' => ReservationStatus::SinConfirmar->value,
                ],
                [
                    'value' => '6. No Recogido',
                    'text' => ReservationStatus::NoRecogido->value,
                ],
            ],
            'categories'    =>  fn() => Category::all(),
            'branches'    =>  fn() => Branch::orderBy('name','asc')->get()->all(),
            'franchises'    =>  fn() => Franchise::all(),
        ]);
    }
}
