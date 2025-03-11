<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Helpers\Order\OrderHelper;
use App\Http\Requests\OrderRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    private $orderHelper;

    public function __construct()
    {
        $this->orderHelper = new OrderHelper;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = [
            'name' => $request->name ?? '',
            'user_id' => $request->user_id ?? '',
            'status' => $request->status ?? '',
            'invoice_number' => $request->invoice_number ?? '',
        ];
        $orders = $this->orderHelper->getAll($filter, $request->page ?? 1, $request->per_page ?? 10, $request->sort ?? '');

        return response()->json([
            'success' => true,
            'data' => OrderResource::collection($orders['data']['data']),
            'meta' => [
                'total' => $orders['data']['total'],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors(),
            ], 422);
        }
        $payload = $request->only([
            'user_id',
            'address_id',
            'address',
            'total_price',
            'status',
            'invoice_number',
        ]);
        $order = $this->orderHelper->create($payload);
        if (!$order['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'data' => new OrderResource($order['data']),
            'message' => 'Order created successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = $this->orderHelper->getById($id);
        if (!$order['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ]);
        }
        return response()->json([
            'success' => true,
            'data' => new OrderResource($order['data']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderRequest $request, string $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors(),
            ], 422);
        }
        $payload = $request->only([
            'user_id',
            'address_id',
            'address',
            'total_price',
            'status',
            'invoice_number',
        ]);
        $order = $this->orderHelper->update($payload, $id ?? 0);
        if (!$order['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'data' => new OrderResource($order['data']),
            'message' => 'Order updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = $this->orderHelper->delete($id);
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete order',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully',
        ]);
    }
}
