<?php

namespace App\Helpers\Cart;

use App\Helpers\Venturo;
use App\Models\CartModel;
use Throwable;

class CartHelper extends Venturo
{
    private $cartModel;

    public function __construct()
    {
        $this->cartModel = new CartModel;
    }

    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
    {
        $carts = $this->cartModel->getAll($filter, $page, $itemPerPage, $sort);
        return [
            'status' => true,
            'data' => $carts,
            'total' => $carts['total'],
        ];
    }

    public function getById(string $id): array
    {
        $cart = $this->cartModel->getById($id);
        if (empty($cart)) {
            return [
                'status' => false,
                'data' => null,
            ];
        }
        return [
            'status' => true,
            'data' => $cart,
        ];
    }

    public function create(array $payload): array
    {
        try {
            $cart = $this->cartModel->store($payload);
            return [
                'status' => true,
                'data' => $cart,
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
            $this->cartModel->edit($payload, $id);
            $cart = $this->getById($id);
            return [
                'status' => true,
                'data' => $cart['data'],
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
            $this->cartModel->drop($id);
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }
}
