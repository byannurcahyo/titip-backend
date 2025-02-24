<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Helpers\Cart\CartHelper;
use App\Http\Requests\CartRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;

class CartController extends Controller
{
    private $cartHelper;

    public function __construct()
    {
        $this->cartHelper = new CartHelper;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = [
            'user_id' => $request->user_id ?? '',
        ];
        $carts = $this->cartHelper->getAll($filter, $request->page ?? 1, $request->per_page ?? 10, $request->sort ?? '');

        return response()->json([
            'success' => true,
            'data' => CartResource::collection($carts['data']['data']),
            'meta' => [
                'total' => $carts['data']['total'],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CartRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors(),
            ], 422);
        }
        $payload = $request->only([
            'user_id',
        ]);
        $cart = $this->cartHelper->create($payload);
        if (!$cart['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create cart',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'data' => new CartResource($cart['data']),
            'message' => 'Cart created successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cart = $this->cartHelper->getById($id);
        if (!$cart['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found',
            ]);
        }
        return response()->json([
            'success' => true,
            'data' => new CartResource($cart['data']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CartRequest $request, string $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors(),
            ], 422);
        }
        $payload = $request->only([
            'user_id',
        ]);
        $cart = $this->cartHelper->update($payload, $id ?? 0);
        if (!$cart['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'data' => new CartResource($cart['data']),
            'message' => 'Cart updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cart = $this->cartHelper->delete($id);
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete cart',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'message' => 'Cart deleted successfully',
        ]);
    }
}
