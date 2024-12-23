<?php

namespace App\Http\Resources\DataProvider;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Traits\HTMLCodeTrait;

class CategoryDataProviderResource extends JsonResource
{
    use HTMLCodeTrait;

    protected $category;

    public function __construct($category) {
        $this->category = $category;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $cloudStorageProviderURI = Str::of(config('filesystems.disks.gcs.storage_api_uri'));
        $imageProdURI = $cloudStorageProviderURI->append($this->category->image);
        $imageDevURI = asset("storage/{$this->category->image}");

        return [
            'id'    =>  $this->category->identification,
            'identification'    =>  $this->category->identification,
            'name'      =>  $this->category->name,
            'category'  =>  $this->category->category,
            'description'   =>  $this->category->description,
            'image'     =>  (App::environment('production')) ? $imageProdURI : $imageDevURI,
            'ad'    =>  $this->noCSSCode($this->category->ad),
            'models'    =>  new CategoryModelDataProviderCollection($this->category->models()->orderByDesc('default')->get()),
            'month_prices'  => new CategoryMonthPriceDataProviderCollection($this->category->monthPrices()->get()),
            'total_coverage_unit_charge' => $this->getTotalCoverageUnitCharge($this->category->identification),
        ];
    }

    public function getTotalCoverageUnitCharge(string $category): int {
        $totalCoveragePriceLowGamma = (int) config('localiza.totalCoveragePriceLowGamma');
        $totalCoveragePriceHighGamma = (int) config('localiza.totalCoveragePriceHighGamma');

        $totalCoverageLowGammaCategories = ['C', 'F', 'FX'];
        $totalCoverageHighGammaCategories = ['GC', 'G4', 'LE'];

        if(in_array($category, $totalCoverageHighGammaCategories))
            return $totalCoveragePriceHighGamma;
        else if(in_array($category, $totalCoverageLowGammaCategories))
            return $totalCoveragePriceLowGamma;

        return 100;
    }
}
