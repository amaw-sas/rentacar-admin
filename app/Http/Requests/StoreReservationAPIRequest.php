<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Branch;
use App\Models\Franchise;
use App\Models\Category;

class StoreReservationAPIRequest extends FormRequest
{
    public string $original_pickup_location;
    public string $original_return_location;
    public string $original_category;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
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
            'return_fee'   =>  ['nullable','numeric'],
            'tax_fee'   =>  ['required','numeric'],
            'iva_fee'   =>  ['required','numeric'],
            'total_price'   =>  ['required','numeric'],
            'franchise' => ['required', 'exists:App\Models\Franchise,id'],
            'user' => ['nullable', 'string'],
            'monthly_mileage' => ['nullable', 'string'],
            'total_insurance' => ['nullable', 'boolean'],
            'total_price_to_pay'   =>  ['required','numeric'],
            'rate_qualifier'   =>  ['required'],
            'reference_token'   =>  ['required','string'],
        ];
    }

    protected function prepareForValidation()
    {
        if($this->pickup_location){
            $pickup_location = Branch::where('code',$this->pickup_location)->firstOrFail();
            $this->original_pickup_location = $this->pickup_location;
            $this->merge([
                'pickup_location'   => $pickup_location->id
            ]);
        }

        if($this->return_location){
            $return_location = Branch::where('code',$this->return_location)->firstOrFail();
            $this->original_return_location = $this->return_location;
            $this->merge([
                'return_location'   => $return_location->id
            ]);
        }

        if($this->franchise){
            $franchise = Franchise::where('name',$this->franchise)->firstOrFail();
            $this->merge([
                'franchise'   => $franchise->id
            ]);
        }

        if($this->category){
            $category = Category::where('identification',$this->category)->firstOrFail();
            $this->original_category = $this->category;
            $this->merge([
                'category'   => $category->id
            ]);
        }

    }
}
