<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\CreateOrder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/** The controller is thin: request parsing, response codes. The logic lives in services (ADR-001). */
class OrderController extends Controller
{
    /** The ceiling for every list: a client cannot ask for everything at once (ADR-002). */
    private const MAX_PER_PAGE = 100;
    private const DEFAULT_PER_PAGE = 20;

    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min((int) $request->integer('per_page', self::DEFAULT_PER_PAGE), self::MAX_PER_PAGE);

        // with('items') kills the N+1 over items (ADR-002).
        $orders = Order::query()->with('items')->latest('id')->paginate($perPage);

        return OrderResource::collection($orders);
    }

    public function show(Order $order): OrderResource
    {
        return new OrderResource($order->load('items'));
    }

    public function store(StoreOrderRequest $request, CreateOrder $createOrder): OrderResource
    {
        return new OrderResource($createOrder->handle($request->validated()));
    }
}
