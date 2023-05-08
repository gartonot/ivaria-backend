<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'username' => $this->resource->username,
            'phone' => $this->resource->phone,
            'created_at' => $this->resource->created_at,
            'dishes' => $this->whenLoaded('dishes', function () {
                return DishResource::collection($this->resource->dishes);
            }),
            'total_price' => $this->resource->totalPrice(),
        ];
    }
}
