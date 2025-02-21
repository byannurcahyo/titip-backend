<?php

namespace App\Helpers\User;

use App\Helpers\Venturo;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;
use Throwable;

/**
 * Helper untuk manajemen user
 * Mengambil data, menambah, mengubah, & menghapus ke tabel m_user
 *
 * @author Wahyu Agung <wahyuagung26@gmail.com>
 */
class UserHelper extends Venturo
{
    const USER_PHOTO_DIRECTORY = 'foto-user';
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel;
    }

    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
    {
        $users = $this->userModel->getAll($filter, $page, $itemPerPage, $sort);
        return [
            'status' => true,
            'data' => $users,
            'total' => $users['total'],
        ];
    }

    public function getById(string $id): array
    {
        $user = $this->userModel->getById($id);
        if (empty($user)) {
            return [
                'status' => false,
                'data' => null,
            ];
        }
        return [
            'status' => true,
            'data' => $user,
        ];
    }

    private function uploadGetPayload(array $payload)
    {
        if (!empty($payload['photo'])) {
            $fileName = $this->generateFileName($payload['photo'], 'USER_'.date('Ymdhis'));
            $photo = $payload['photo']->storeAs(self::USER_PHOTO_DIRECTORY, $fileName, 'public');
            $payload['photo'] = $photo;
        } else {
            unset($payload['photo']);
        }
        return $payload;
    }

    public function create(array $payload): array
    {
        try {
            $payload['password'] = Hash::make($payload['password']);
            $payload = $this->uploadGetPayload($payload);
            $user = $this->userModel->store($payload);
            return [
                'status' => true,
                'data' => $user,
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
            if (isset($payload['password']) && !empty($payload['password'])) {
                $payload['password'] = Hash::make($payload['password']);
            } else {
                unset($payload['password']);
            }
            $payload = $this->uploadGetPayload($payload);
            $this->userModel->edit($payload, $id);
            $user = $this->getById($id);
            return [
                'status' => true,
                'data' => $user['data'],
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
            $this->userModel->drop($id);

            return true;
        } catch (Throwable $e) {
            return false;
        }
    }
}
