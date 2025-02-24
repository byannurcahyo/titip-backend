<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Helpers\Address\AddressHelper;
use App\Http\Resources\AddressResource;

class AddressController extends Controller
{
    private $addressHelper;

    public function __construct()
    {
        $this->addressHelper = new AddressHelper;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = [
            'user_id' => $request->user_id ?? '',
            'city' => $request->city ?? '',
            'province' => $request->province ?? '',
            'postal_code' => $request->postal_code ?? '',
        ];
        $addresses = $this->addressHelper->getAll($filter, $request->page ?? 1, $request->per_page ?? 10, $request->sort ?? '');

        return response()->json([
            'success' => true,
            'data' => AddressResource::collection($addresses['data']['data']),
            'meta' => [
                'total' => $addresses['data']['total'],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddressRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors(),
            ], 422);
        }
        $payload = $request->only([
            'user_id',
            'address',
            'city',
            'province',
            'postal_code',
            'phone',
        ]);
        $address = $this->addressHelper->create($payload);
        if (!$address['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create address',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'data' => new AddressResource($address['data']),
            'message' => 'Address created successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $address = $this->addressHelper->getById($id);
        if (!$address['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Address not found',
            ]);
        }
        return response()->json([
            'success' => true,
            'data' => new AddressResource($address['data']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AddressRequest $request, string $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors(),
            ], 422);
        }
        $payload = $request->only([
            'user_id',
            'address',
            'city',
            'province',
            'postal_code',
            'phone',
        ]);
        $address = $this->addressHelper->update($payload, $id ?? 0);
        if (!$address['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update address',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'data' => new AddressResource($address['data']),
            'message' => 'Address updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $address = $this->addressHelper->delete($id);
        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete address',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'message' => 'Address deleted successfully',
        ]);
    }
}
