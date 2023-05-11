<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\OrderManager;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    private const ORDERS_PER_PAGE = 15;

    public function index(Request $request)
    {
        $orders = Order::with('dishes');
        if ($tel = $request->get('tel')) {
            $orders->where('phone', 'LIKE', "%$tel%");
        }
        return OrderResource::collection($orders->paginate(self::ORDERS_PER_PAGE));
    }

    public function store(OrderRequest $request)
    {
        return new OrderResource(app(OrderManager::class, ['order' => null])->store($request->validated()));
    }

    public function destroy(Request $request, $orderId)
    {
        app(OrderManager::class, ['order' => Order::query()->findOrFail($orderId)])->delete();
        return response()->noContent();
    }

    public function update(OrderRequest $request, $orderId)
    {
        return app(OrderManager::class, ['order' => Order::query()->findOrFail($orderId)])
            ->update($request->validated());
    }
}
