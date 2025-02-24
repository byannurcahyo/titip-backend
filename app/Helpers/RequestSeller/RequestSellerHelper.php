<?php

namespace App\Helpers\RequestSeller;

use Throwable;
use App\Helpers\Venturo;
use App\Models\UserModel;
use App\Models\RequestSellerModel;

class RequestSellerHelper extends Venturo
{
    private $requestSellerModel;

    public function __construct()
    {
        $this->requestSellerModel = new RequestSellerModel;
    }

    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
    {
        $requestSellers = $this->requestSellerModel->getAll($filter, $page, $itemPerPage, $sort);
        return [
            'status' => true,
            'data' => $requestSellers,
            'total' => $requestSellers['total'],
        ];
    }

    public function getById(string $id): array
    {
        $requestSeller = $this->requestSellerModel->getById($id);
        if (empty($requestSeller)) {
            return [
                'status' => false,
                'data' => null,
            ];
        }
        return [
            'status' => true,
            'data' => $requestSeller,
        ];
    }

    public function create(array $payload): array
    {
        try {
            $user = UserModel::find($payload['user_id']);
            if (!$user || $user->role !== 'user') {
                return [
                    'status' => false,
                    'error' => 'Only users with role "user" can create a request seller.',
                ];
            }
            $requestSeller = $this->requestSellerModel->store($payload);
            return [
                'status' => true,
                'data' => $requestSeller,
            ];
        } catch (Throwable $e) {
            return [
                'status' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function update(array $payload, string $id): array
    {
        try {
            $requestSeller = $this->requestSellerModel->edit($payload, $id);
            return [
                'status' => true,
                'data' => $requestSeller,
            ];
        } catch (Throwable $e) {
            return [
                'status' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function delete(string $id): bool
    {
        try {
            return $this->requestSellerModel->drop($id);
        } catch (Throwable $e) {
            return false;
        }
    }
}
