<?php

namespace App\Models;

use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellerModel extends Model implements CrudInterface
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'store_name',
    ];
    protected $table = 'sellers';

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }
    public function products()
    {
        return $this->hasMany(ProductModel::class, 'seller_id', 'id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItemModel::class, 'seller_id', 'id');
    }

    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
    {
        $skip = ($page * $itemPerPage) - $itemPerPage;
        $request = $this->query();
        if (!empty($filter['store_name'])) {
            $request->where('store_name', 'LIKE', '%'.$filter['store_name'].'%');
        }
        if (!empty($filter['user_id'])) {
            $request->where('user_id', 'LIKE', '%'.$filter['user_id'].'%');
        }
        $total = $request->count();
        $sort = $sort ?: 'id DESC';
        $list = $request->skip($skip)->take($itemPerPage)->orderByRaw($sort)->get();
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
        $seller = $this->find($id);
        if ($seller) {
            $seller->update($payload);
            return $seller;
        }
        return null;
    }

    public function drop(string $id)
    {
        return $this->find($id)->delete();
    }
}
