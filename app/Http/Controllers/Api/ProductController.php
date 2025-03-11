<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Helpers\Product\ProductHelper;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    private $productHelper;

    public function __construct()
    {
        $this->productHelper = new ProductHelper;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = [
            'name' => $request->name ?? '',
            'seller_id' => $request->seller_id ?? '',
        ];
        $products = $this->productHelper->getAll($filter, $request->page ?? 1, $request->per_page ?? 10, $request->sort ?? '');

        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($products['data']['data']),
            'meta' => [
                'total' => $products['data']['total'],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors(),
            ], 422);
        }
        $payload = $request->only([
            'seller_id',
            'name',
            'description',
            'price',
            'stock',
            'photo',
        ]);
        $product = $this->productHelper->create($payload);
        if (!$product['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'data' => new ProductResource($product['data']),
            'message' => 'Product created successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = $this->productHelper->getById($id);
        if (!$product['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ]);
        }
        return response()->json([
            'success' => true,
            'data' => new ProductResource($product['data']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors(),
            ], 422);
        }
        $payload = $request->only([
            'seller_id',
            'name',
            'description',
            'price',
            'stock',
            'photo',
        ]);
        $product = $this->productHelper->update($payload, $id ?? 0);
        if (!$product['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'data' => new ProductResource($product['data']),
            'message' => 'Product updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = $this->productHelper->delete($id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully',
        ]);
    }
}
