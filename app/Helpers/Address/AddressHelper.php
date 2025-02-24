<?php

namespace App\Helpers\Address;

use App\Helpers\Venturo;
use App\Models\AddressModel;
use Throwable;

class AddressHelper extends Venturo
{
    private $addressModel;

    public function __construct()
    {
        $this->addressModel = new AddressModel;
    }

    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
    {
        $addresses = $this->addressModel->getAll($filter, $page, $itemPerPage, $sort);
        return [
            'status' => true,
            'data' => $addresses,
            'total' => $addresses['total'],
        ];
    }

    public function getById(string $id): array
    {
        $address = $this->addressModel->getById($id);
        if (empty($address)) {
            return [
                'status' => false,
                'data' => null,
            ];
        }
        return [
            'status' => true,
            'data' => $address,
        ];
    }

    public function create(array $payload): array
    {
        try {
            $address = $this->addressModel->store($payload);
            return [
                'status' => true,
                'data' => $address,
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
            $this->addressModel->edit($payload, $id);
            $address = $this->getById($id);
            return [
                'status' => true,
                'data' => $address['data'],
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
            $this->addressModel->drop($id);
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }
}
