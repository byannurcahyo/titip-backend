<?php

namespace App\Models;

use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddressModel extends Model implements CrudInterface
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'address',
        'city',
        'province',
        'postal_code',
        'phone',
    ];
    protected $table = 'address';

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }
    public function order()
    {
        return $this->hasMany(OrderModel::class, 'address_id', 'id');
    }

    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
    {
        $skip = ($page * $itemPerPage) - $itemPerPage;
        $address = $this->query();
        if (!empty($filter['user_id'])) {
            $address->where('user_id', 'LIKE', '%'.$filter['user_id'].'%');
        }
        if (!empty($filter['city'])) {
            $address->where('city', 'LIKE', '%'.$filter['city'].'%');
        }
        if (!empty($filter['province'])) {
            $address->where('province', 'LIKE', '%'.$filter['province'].'%');
        }
        if (!empty($filter['postal_code'])) {
            $address->where('postal_code', 'LIKE', '%'.$filter['postal_code'].'%');
        }
        $total = $address->count();
        $sort = $sort ?: 'id DESC';
        $list = $address->skip($skip)->take($itemPerPage)->orderByRaw($sort)->get();
        return [
            'total' => $total,
            'data' => $list,
        ];
    }

    public function getById(string $id)
    {
        return $this->find($id);
    }

    public function store(array $payload)
    {
        return $this->create($payload);
    }

    public function edit(array $payload, string $id)
    {
        return $this->find($id)->update($payload);
    }

    public function drop(string $id)
    {
        return $this->find($id)->delete();
    }
}
