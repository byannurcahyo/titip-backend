<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderItemRequest;
use App\Http\Resources\OrderItemResource;
use App\Helpers\Order\OrderItemHelper;

class OrderItemController extends Controller
{
    private $orderItemHelper;

    public function __construct()
    {
        $this->orderItemHelper = new OrderItemHelper;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = [
            'order_id' => $request->order_id ?? '',
            'product_id' => $request->product_id ?? '',
        ];
        $orderItems = $this->orderItemHelper->getAll($filter, $request->page ?? 1, $request->per_page ?? 10, $request->sort ?? '');

        return response()->json([
            'success' => true,
            'data' => OrderItemResource::collection($orderItems['data']['data']),
            'meta' => [
                'total' => $orderItems['data']['total'],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderItemRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors(),
            ], 422);
        }
        $payload = $request->only([
            'order_id',
            'seller_id',
            'product_id',
            'product_name',
            'price',
            'description',
            'quantity',
            'subTotal',
        ]);
        $orderItem = $this->orderItemHelper->create($payload);
        if (!$orderItem['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order item',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'data' => new OrderItemResource($orderItem['data']),
            'message' => 'Order item created successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $orderItem = $this->orderItemHelper->getById($id);
        if (!$orderItem['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Order item not found',
            ]);
        }
        return response()->json([
            'success' => true,
            'data' => new OrderItemResource($orderItem['data']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderItemRequest $request, string $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors(),
            ], 422);
        }
        $payload = $request->only([
            'order_id',
            'seller_id',
            'product_id',
            'product_name',
            'price',
            'description',
            'quantity',
            'subTotal',
        ]);
        $orderItem = $this->orderItemHelper->update($payload, $id ?? 0);
        if (!$orderItem['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order item',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'data' => new OrderItemResource($orderItem['data']),
            'message' => 'Order item updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $orderItem = $this->orderItemHelper->delete($id);
        if (!$orderItem) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete order item',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'message' => 'Order item deleted successfully',
        ]);
    }
}
