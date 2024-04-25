<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends StoreReservationRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fullname'  =>  ['required','min:5', 'max:50'],
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
        ];
    }
}
