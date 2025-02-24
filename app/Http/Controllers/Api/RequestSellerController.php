<?php

namespace App\Http\Controllers\api;

use App\Models\UserModel;
use Illuminate\Http\Request;
use App\Helpers\User\UserHelper;
use App\Helpers\Seller\SellerHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequestSellerRequest;
use App\Http\Resources\RequestSellerResource;
use App\Helpers\RequestSeller\RequestSellerHelper;

class RequestSellerController extends Controller
{
    private $requestSellerHelper;
    private $userHelper;
    private $sellerHelper;

    public function __construct()
    {
        $this->requestSellerHelper = new RequestSellerHelper;
        $this->userHelper = new UserHelper;
        $this->sellerHelper = new SellerHelper;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = [
            'user_id' => $request->user_id ?? '',
            'status' => $request->status ?? '',
        ];
        $requestSellers = $this->requestSellerHelper->getAll($filter, $request->page ?? 1, $request->per_page ?? 10, $request->sort ?? '');

        return response()->json([
            'success' => true,
            'data' => RequestSellerResource::collection($requestSellers['data']['data']),
            'meta' => [
                'total' => $requestSellers['data']['total'],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestSellerRequest $request)
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
                'message' => 'Only role "user" can create a request seller',
            ], 403);
        }
        $payload = $request->only([
            'user_id',
            'product_id',
            'status',
        ]);
        $requestSeller = $this->requestSellerHelper->create($payload);
        if (!$requestSeller['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create Request Seller',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'data' => new RequestSellerResource($requestSeller['data']),
            'message' => 'Request Seller created successfully',
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $requestSeller = $this->requestSellerHelper->getById($id);
        if (!$requestSeller['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Request Seller not found',
            ]);
        }
        return response()->json([
            'success' => true,
            'data' => new RequestSellerResource($requestSeller['data']),
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(RequestSellerRequest $request, string $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors()->first(),
            ], 422);
        }
        $payload = $request->only([
            'user_id',
            'product_id',
            'status',
        ]);
        $payload['reviewed_at'] = now();
        $requestSeller = $this->requestSellerHelper->update($payload, $id);
        if (!$requestSeller['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Request Seller',
            ], 500);
        }
        if ($payload['status'] === 'approved') {
            $userId = $payload['user_id'];
            $user = UserModel::find($userId);
            if ($user) {
                $updateUserPayload = ['role' => 'seller'];
                $this->userHelper->update($updateUserPayload, $userId);

                $sellerPayload = [
                    'user_id' => $userId,
                    'store_name' => $user->name . ' Store',
                ];
                $this->sellerHelper->create($sellerPayload);
            }
        }
        return response()->json([
            'success' => true,
            'data' => new RequestSellerResource($requestSeller['data']),
            'message' => 'Request Seller updated successfully',
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $requestSeller = $this->requestSellerHelper->delete($id);
        if (!$requestSeller) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete Request Seller',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'message' => 'Request Seller deleted successfully',
        ]);
    }
}
