<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class DishResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'img_src' => $this->resource->img_src,
            'created_at' => $this->resource->created_at,
            'deleted_at' => $this->resource->deleted_at,
            $this->mergeWhen($this->resource->pivot, [
                'price' => $this->resource->pivot->price,
                'count' => $this->resource->pivot->count,
            ])
        ];
    }
}
