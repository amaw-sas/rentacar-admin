<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fullname'  =>  ['required','min:1', 'max:50'],
            'identification_type'   =>  ['required', 'string'],
            'identification'  =>  ['required','min:5', 'max:50'],
            'phone'  =>  ['required','min:5', 'max:50'],
            'email'  =>  ['required','email'],
            'category'  =>  ['required', 'exists:App\Models\Category,id'],
            'pickup_location' => ['required', 'exists:App\Models\Branch,id'],
            'return_location' => ['required', 'exists:App\Models\Branch,id'],
            'pickup_date'   =>  ['required','date'],
            'return_date'   =>  ['required','date'],
            'pickup_hour'   =>  ['required','string'],
            'return_hour'   =>  ['required','string'],
            'selected_days'   =>  ['required','numeric'],
            'extra_hours'   =>  ['nullable','numeric'],
            'extra_hours_price'   =>  ['nullable','numeric'],
            'coverage_days'   =>  ['required','numeric'],
            'coverage_price'   =>  ['required','numeric'],
            'tax_fee'   =>  ['required','numeric'],
            'iva_fee'   =>  ['required','numeric'],
            'total_price'   =>  ['required','numeric'],
            'total_price_localiza'   =>  ['nullable','numeric'],
            'franchise' => ['required', 'exists:App\Models\Franchise,id'],
            'reserve_code'   =>  ['nullable',"string"],
            'user' => ['nullable', 'string'],
            'status' => ['required', 'string'],
            'monthly_mileage' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'fullname'  => 'Nombre completo',
            'identification_type'  => 'Tipo de identificación',
            'identification'  => 'Identificación',
            'phone'  => 'Teléfono',
            'email' => 'Correo eléctronico',
            'category' => 'Categoría',
            'pickup_location' => 'Lugar de recogida',
            'return_location' => 'Lugar de retorno',
            'pickup_date' => 'Día de recogida',
            'return_date' => 'Día de retorno',
            'pickup_hour' => 'Hora de recogida',
            'return_hour' => 'Hora de retorno',
            'selected_days' => 'Días reservados',
            'extra_hours' => 'Horas extras',
            'extra_hours_price' => 'Precio horas extras',
            'coverage_days' => 'Días seguro',
            'coverage_price' => 'Precio seguro',
            'tax_fee' => 'Tasa administrativa',
            'iva_fee' => 'Precio IVA',
            'total_price' => 'Precio final',
            'total_price_localiza' => 'Precio final Localiza',
            'franchise' => 'Franquicia',
            'reserve_code' => 'Código reserva',
            'user' => 'Referido',
            'status' => 'Estado',
            'monthly_mileage' => 'Kilometraje',
        ];
    }
}
