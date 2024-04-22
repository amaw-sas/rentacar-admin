<?php

namespace App\Http\Resources\DataProvider;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryModelDataProviderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $cloudStorageProviderURI = Str::of(config('filesystems.disks.gcs.storage_api_uri'));
        $imageProdURI = $cloudStorageProviderURI->append($this->image);
        $imageDevURI = asset("storage/carcategories/car.png");

        return [
            'name'    =>  $this->name,
            'image'     =>  (App::environment('production')) ? $imageProdURI : $imageDevURI,
            'description'   =>  $this->description,
            'default'   =>  ($this->default) ? true : false,
        ];
    }
}
