<?php

namespace App\Helpers\Product;

use App\Helpers\Venturo;
use App\Models\ProductModel;
use Throwable;

class ProductHelper extends Venturo
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel;
    }

    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
    {
        $products = $this->productModel->getAll($filter, $page, $itemPerPage, $sort);
        return [
            'status' => true,
            'data' => $products,
            'total' => $products['total'],
        ];
    }

    public function getById(string $id): array
    {
        $product = $this->productModel->getById($id);
        if (empty($product)) {
            return [
                'status' => false,
                'data' => null,
            ];
        }
        return [
            'status' => true,
            'data' => $product,
        ];
    }

    private function uploadGetPayload(array $payload)
    {
        if (!empty($payload['photo'])) {
            $fileName = $this->generateFileName($payload['photo'], 'PRODUCT_'.date('Ymdhis'));
            $photo = $payload['photo']->storeAs('product-photos', $fileName, 'public');
            $payload['photo'] = $photo;
        } else {
            unset($payload['photo']);
        }
        return $payload;
    }

    public function create(array $payload): array
    {
        try {
            $payload = $this->uploadGetPayload($payload);
            $product = $this->productModel->store($payload);
            return [
                'status' => true,
                'data' => $product,
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
            $payload = $this->uploadGetPayload($payload);
            $this->productModel->edit($payload, $id);
            $product = $this->getById($id);
            return [
                'status' => true,
                'data' => $product['data'],
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
            $this->productModel->drop($id);
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }
}
