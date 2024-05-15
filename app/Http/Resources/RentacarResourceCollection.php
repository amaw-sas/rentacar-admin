<?php

namespace App\Http\Resources;

use App\Rentcar\FilterManager;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RentacarResourceCollection extends ResourceCollection
{
    public $table = null;
    public $filterManager;

    public function __construct(mixed $resource)
    {
        $this->filterManager = new FilterManager($this->table);
        parent::__construct($resource);
    }

    public function with(Request $request)
    {
        return [
            'meta' => [
                'orderByCol'        =>  $this->filterManager->getFilter('orderByCol'),
                'orderOrientation'  =>  $this->filterManager->getFilter('orderOrientation'),
                'filterCols'        =>  $this->filterManager->getFilter('filterCols'),
                'filterDateRanges'  =>  $this->filterManager->getFilter('filterDateRanges'),
                'query'             =>  $this->filterManager->getFilter('query'),
            ]
        ];
    }

}
