<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RentacarResourceCollection extends ResourceCollection
{
    public function with(Request $request)
    {
        return [
            'meta' => [
                'orderBy'   =>  [
                    'col' => $request->input('orderByCol', $this->orderByCols[0]),
                    'order' => $request->input('orderOrientation', $this->orderByCols[1])
                ],
                'filterCols' =>  $request->input('filterCols'),
                'filterDateRanges' =>  $request->input('filterDateRanges'),
                'query' =>  $request->input('query'),
            ]
        ];
    }
}
