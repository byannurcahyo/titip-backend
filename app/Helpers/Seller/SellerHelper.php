<?php

namespace App\Helpers\Seller;

use App\Helpers\Venturo;
use App\Models\SellerModel;
use Throwable;

class SellerHelper extends Venturo
{
    private $sellerModel;

    public function __construct()
    {
        $this->sellerModel = new SellerModel;
    }

    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
    {
        $sellers = $this->sellerModel->getAll($filter, $page, $itemPerPage, $sort);
        return [
            'status' => true,
            'data' => $sellers,
            'total' => $sellers['total'],
        ];
    }

    public function getById(string $id): array
    {
        $seller = $this->sellerModel->getById($id);
        if (empty($seller)) {
            return [
                'status' => false,
                'data' => null,
            ];
        }
        return [
            'status' => true,
            'data' => $seller,
        ];
    }

    public function create(array $payload): array
    {
        try {
            $seller = $this->sellerModel->store($payload);
            return [
                'status' => true,
                'data' => $seller,
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
            $seller = $this->sellerModel->edit($payload, $id);
            return [
                'status' => true,
                'data' => $seller,
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
            return $this->sellerModel->drop($id);
        } catch (Throwable $e) {
            return false;
        }
    }
}
