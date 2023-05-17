<?php

namespace App\Http\Resources;

class DishResource extends DishShortResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            array_merge(parent::toArray($request), [
                $this->mergeWhen($this->resource->pivot, [
                    'price' => $this->resource->pivot->price,
                    'count' => $this->resource->pivot->count,
                ])
            ])
        ];
    }
}
