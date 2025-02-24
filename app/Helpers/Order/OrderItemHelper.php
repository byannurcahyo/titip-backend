<?php

namespace App\Helpers\Order;

use App\Helpers\Venturo;
use App\Models\OrderItemModel;
use Throwable;

class OrderItemHelper extends Venturo
{
    private $orderItemModel;

    public function __construct()
    {
        $this->orderItemModel = new OrderItemModel;
    }

    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
    {
        $orderItems = $this->orderItemModel->getAll($filter, $page, $itemPerPage, $sort);
        return [
            'status' => true,
            'data' => $orderItems,
            'total' => $orderItems['total'],
        ];
    }

    public function getById(string $id): array
    {
        $orderItem = $this->orderItemModel->getById($id);
        if (empty($orderItem)) {
            return [
                'status' => false,
                'data' => null,
            ];
        }
        return [
            'status' => true,
            'data' => $orderItem,
        ];
    }

    public function create(array $payload): array
    {
        try {
            $orderItem = $this->orderItemModel->store($payload);
            return [
                'status' => true,
                'data' => $orderItem,
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
            $this->orderItemModel->edit($payload, $id);
            $orderItem = $this->getById($id);
            return [
                'status' => true,
                'data' => $orderItem['data'],
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
            $this->orderItemModel->drop($id);
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }
}
