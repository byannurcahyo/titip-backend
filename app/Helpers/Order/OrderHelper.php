<?php

namespace App\Helpers\Order;

use App\Helpers\Venturo;
use App\Models\OrderModel;
use Throwable;

class OrderHelper extends Venturo
{
    private $orderModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel;
    }

    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
    {
        $orders = $this->orderModel->getAll($filter, $page, $itemPerPage, $sort);
        return [
            'status' => true,
            'data' => $orders,
            'total' => $orders['total'],
        ];
    }

    public function getById(string $id): array
    {
        $order = $this->orderModel->getById($id);
        if (empty($order)) {
            return [
                'status' => false,
                'data' => null,
            ];
        }
        return [
            'status' => true,
            'data' => $order,
        ];
    }

    public function create(array $payload): array
    {
        try {
            $order = $this->orderModel->store($payload);
            return [
                'status' => true,
                'data' => $order,
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
            $this->orderModel->edit($payload, $id);
            $order = $this->getById($id);
            return [
                'status' => true,
                'data' => $order['data'],
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
            $this->orderModel->drop($id);
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }
}
