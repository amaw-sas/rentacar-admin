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
    protected $totalCoverageSet;

    public function __construct($category) {
        $this->category = $category;
        $this->totalCoverageSet = [
            'C' => (int) config('localiza.totalCoverageCategoryC'),
            'F' => (int) config('localiza.totalCoverageCategoryF'),
            'FX' => (int) config('localiza.totalCoverageCategoryFX'),
            'FL' => (int) config('localiza.totalCoverageCategoryFL'),
            'FU' => (int) config('localiza.totalCoverageCategoryFU'),
            'GC' => (int) config('localiza.totalCoverageCategoryGC'),
            'GL' => (int) config('localiza.totalCoverageCategoryGL'),
            'G4' => (int) config('localiza.totalCoverageCategoryG4'),
            'GR' => (int) config('localiza.totalCoverageCategoryGR'),
            'GY' => (int) config('localiza.totalCoverageCategoryGY'),
            'LE' => (int) config('localiza.totalCoverageCategoryLE'),
            'VP' => (int) config('localiza.totalCoverageCategoryVP'),
        ];
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
        if(array_key_exists($category, $this->totalCoverageSet))
            return $this->totalCoverageSet[$category];

        return 100;
    }
}
