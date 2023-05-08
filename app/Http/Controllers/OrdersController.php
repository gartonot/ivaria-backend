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
        return new OrderResource(app(OrderManager::class)->store($request->validated()));
    }
}
