<?php

namespace App;

use App\Events\OrderCreated;
use App\Models\Dishes;
use App\Models\Order;

class OrderManager
{
    public function store(array $params): Order
    {
        $order = new Order();
        $order->fill($params);
        $order->save();
        $order->dishes()->sync(collect($params['dishes'])->mapWithKeys(function ($item) {
            return [
                $item['id'] =>
                    [
                        'count' => $item['count'],
                        'price' => Dishes::find($item['id'])->first()->price,
                    ],
            ];
        }));
        event(new OrderCreated($order->load('dishes')));

        return $order;
    }
}
