<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationEmailPreviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $image = $this->categoryObject->models->first()->image;
        $cloudStorageProviderURI = Str::of(config('filesystems.disks.gcs.storage_api_uri'));
        $imageProdURI = $cloudStorageProviderURI->append($image);
        $imageDevURI = asset("storage/carcategories/car.png");

        $category = Str::after($this->categoryObject->category, $this->categoryObject->name);
        $description = Str::words($this->categoryObject->description, 3, '');



        return [
            'fullname' => $this->fullname,
            'identification_type' => $this->short_identification_type,
            'identification' => $this->identification,
            'reserve_code' => $this->reserve_code,
            'category_name' => $this->categoryObject->name,
            'category_category' => $category,
            'category_description' => $description,
            'category_image' => (App::environment('production')) ? $imageProdURI : $imageDevURI,
            'selected_days'  =>  $this->selected_days,
            'pickup_branch'  =>  $this->pickupLocation->name,
            'pickup_city'  =>  $this->formatted_pickup_city,
            'pickup_date'  =>  $this->formatted_pickup_date,
            'pickup_hour'  =>  $this->formatted_pickup_hour,
            'return_branch'  =>  $this->returnLocation->name,
            'return_city'  =>  $this->formatted_return_city,
            'return_date'  =>  $this->formatted_return_date,
            'return_hour'  =>  $this->formatted_return_hour,
            'extra_hours'  =>  $this->extra_hours,
            'extra_hours_price' => $this->formatted_extra_hours_price,
            'coverage_price' => $this->formatted_coverage_price,
            'return_fee' => ($this->return_fee > 0) ? $this->formatted_return_fee : 0,
            'tax_fee' => $this->formatted_tax_fee_from_localiza_price,
            'iva_fee' => $this->formatted_iva_fee_from_localiza_price,
            'subtotal_fee' => $this->formatted_subtotal_from_localiza_price,
            'total_fee' => $this->formatted_total_price_localiza,
            'base_fee' => $this->formatted_base_price_from_localiza_price,
            'daily_base_fee' => $this->formatted_original_vehicle_unit_price,
            'discount_percentage' => $this->formatted_discount_percentage_from_localiza_price,
            'discount_amount' => $this->formatted_daily_base_price_from_localiza_price,
            'included_fees'  =>  $this->formatted_included_fees,
        ];
    }


}
