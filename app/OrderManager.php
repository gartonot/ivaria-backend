<?php

namespace App;

use App\Events\OrderCreated;
use App\Models\Dishes;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderManager
{
    private $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function store(array $params): Order
    {
        $this->order = new Order();
        $this->order->fill($params);
        $this->order->save();
        $this->order->dishes()->sync(collect($params['dishes'])->mapWithKeys(function ($item) {
            return [
                $item['id'] =>
                    [
                        'count' => $item['count'],
                        'price' => Dishes::find($item['id'])->first()->price,
                    ],
            ];
        }));
        event(new OrderCreated($this->order->load('dishes')));

        return $this->order;
    }

    public function update(array $params): Order
    {
        if (empty($this->order)) {
            throw new \RuntimeException('order is null');
        }

        $this->order->update($params);
        $this->order->dishes()->sync(collect($params['dishes'])->mapWithKeys(function ($item) {
            return [
                $item['id'] =>
                    [
                        'count' => $item['count'],
                        'price' => Dishes::find($item['id'])->first()->price,
                    ],
            ];
        }));

        return $this->order;
    }

    public function delete(): void
    {
        DB::beginTransaction();
        $this->order->dishes()->detach();
        $this->order->delete();
        DB::commit();
    }
}
