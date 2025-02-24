<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Helpers\Cart\CartItemHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CartItemRequest;
use App\Http\Resources\CartItemResource;

class CartItemController extends Controller
{
    private $cartItemHelper;

    public function __construct()
    {
        $this->cartItemHelper = new CartItemHelper;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = [
            'cart_id' => $request->cart_id ?? '',
            'product_id' => $request->product_id ?? '',
        ];
        $cartItems = $this->cartItemHelper->getAll($filter, $request->page ?? 1, $request->per_page ?? 10, $request->sort ?? '');

        return response()->json([
            'success' => true,
            'data' => CartItemResource::collection($cartItems['data']['data']),
            'meta' => [
                'total' => $cartItems['data']['total'],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CartItemRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors(),
            ], 422);
        }
        $payload = $request->only([
            'cart_id',
            'product_id',
            'quantity',
        ]);
        $cartItem = $this->cartItemHelper->create($payload);
        if (!$cartItem['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create cart item',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'data' => new CartItemResource($cartItem['data']),
            'message' => 'Cart item created successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cartItem = $this->cartItemHelper->getById($id);
        if (!$cartItem['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found',
            ]);
        }
        return response()->json([
            'success' => true,
            'data' => new CartItemResource($cartItem['data']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CartItemRequest $request, string $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors(),
            ], 422);
        }
        $payload = $request->only([
            'cart_id',
            'product_id',
            'quantity',
        ]);
        $cartItem = $this->cartItemHelper->update($payload, $id ?? 0);
        if (!$cartItem['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart item',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'data' => new CartItemResource($cartItem['data']),
            'message' => 'Cart item updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cartItem = $this->cartItemHelper->delete($id);
        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete cart item',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'message' => 'Cart item deleted successfully',
        ]);
    }
}
