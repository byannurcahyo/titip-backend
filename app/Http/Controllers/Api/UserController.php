<?php

namespace App\Http\Controllers\Api;

use App\Helpers\User\UserHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userHelper;

    public function __construct()
    {
        $this->userHelper = new UserHelper;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = [
            'name' => $request->name ?? '',
            'email' => $request->email ?? '',
        ];
        $users = $this->userHelper->getAll($filter, $request->page ?? 1, $request->per_page ?? 10, $request->sort ?? '');

        return response()->json([
            'success' => true,
            'data' => UserResource::collection($users['data']['data']),
            'meta' => [
                'total' => $users['data']['total'],
            ],
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->userHelper->getById($id);
        if (!$user['status']) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ]);
        }
        return response()->json([
            'success' => true,
            'data' => new UserResource($user['data']),
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors(),
            ], 422);
        }
        $payload = $request->only(['email', 'name', 'password', 'role', 'phone_number', 'photo']);
        $user = $this->userHelper->create($payload);
        if (!$user['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'data' => new UserResource($user['data']),
            'message' => 'User created successfully',
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors(),
            ], 422);
        }
        $payload = $request->only(['email', 'name', 'password', 'role', 'phone_number', 'photo']);
        $user = $this->userHelper->update($payload, $id ?? 0);
        if (!$user['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'data' => new UserResource($user['data']),
            'message' => 'User updated successfully',
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = $this->userHelper->delete($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ]);
    }
}
