<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DishShortResource extends JsonResource
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
            'price' => $this->resource->price,
            'created_at' => $this->resource->created_at,
            'deleted_at' => $this->resource->deleted_at,
            'updated_at' => $this->resource->updated_at,
            'category' => new DishCategoryResource($this->whenLoaded('dish_categories')),
        ];
    }
}
