<?php

namespace App\Http\Controllers\Api;

use App\Models\UserModel;
use Illuminate\Http\Request;
use App\Helpers\Seller\SellerHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SellerRequest;
use App\Http\Resources\SellerResource;

class SellerController extends Controller
{
    private $sellerHelper;

    public function __construct()
    {
        $this->sellerHelper = new SellerHelper;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = [
            'user_id' => $request->user_id ?? '',
            'store_name' => $request->store_name ?? '',
        ];
        $sellers = $this->sellerHelper->getAll($filter, $request->page ?? 1, $request->per_page ?? 10, $request->sort ?? '');

        return response()->json([
            'success' => true,
            'data' => SellerResource::collection($sellers['data']['data']),
            'meta' => [
                'total' => $sellers['data']['total'],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SellerRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors()->first(),
            ], 422);
        }
        $user = UserModel::find($request->user_id);
        if (!$user || $user->role !== 'user') {
            return response()->json([
                'success' => false,
                'message' => 'Only users with role "user" can create a seller.',
            ], 422);
        }
        $payload = $request->only([
            'user_id',
            'store_name',
        ]);
        $seller = $this->sellerHelper->create($payload);
        if (!$seller['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create Seller',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'data' => new SellerResource($seller['data']),
            'message' => 'Seller created successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $seller = $this->sellerHelper->getById($id);
        if (!$seller['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Seller not found',
            ]);
        }
        return response()->json([
            'success' => true,
            'data' => new SellerResource($seller['data']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SellerRequest $request, string $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors()->first(),
            ], 422);
        }
        $payload = $request->only([
            'user_id',
            'store_name',
        ]);
        $seller = $this->sellerHelper->update($payload, $id);
        if (!$seller['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Seller',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'data' => new SellerResource($seller['data']),
            'message' => 'Seller updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $seller = $this->sellerHelper->delete($id);
        if (!$seller) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete Seller',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'message' => 'Seller deleted successfully',
        ]);
    }
}
