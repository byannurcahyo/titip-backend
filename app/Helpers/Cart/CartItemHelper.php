<?php

namespace App\Helpers\Cart;

use App\Helpers\Venturo;
use App\Models\CartItemModel;
use Throwable;

class CartItemHelper extends Venturo
{
    private $cartItemModel;

    public function __construct()
    {
        $this->cartItemModel = new CartItemModel;
    }

    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
    {
        $cartItems = $this->cartItemModel->getAll($filter, $page, $itemPerPage, $sort);
        return [
            'status' => true,
            'data' => $cartItems,
            'total' => $cartItems['total'],
        ];
    }

    public function getById(string $id): array
    {
        $cartItem = $this->cartItemModel->getById($id);
        if (empty($cartItem)) {
            return [
                'status' => false,
                'data' => null,
            ];
        }
        return [
            'status' => true,
            'data' => $cartItem,
        ];
    }

    public function create(array $payload): array
    {
        try {
            $cartItem = $this->cartItemModel->store($payload);
            return [
                'status' => true,
                'data' => $cartItem,
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
            $this->cartItemModel->edit($payload, $id);
            $cartItem = $this->getById($id);
            return [
                'status' => true,
                'data' => $cartItem['data'],
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
            $this->cartItemModel->drop($id);
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }
}
