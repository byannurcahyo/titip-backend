<?php

namespace App\Models;

use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartModel extends Model implements CrudInterface
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;
    public $timestamps = true;
    protected $fillable = [
        'user_id',
    ];
    protected $table = 'carts';

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }
    public function cartItems()
    {
        return $this->hasMany(CartItemModel::class, 'cart_id', 'id');
    }

    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
    {
        $skip = ($page * $itemPerPage) - $itemPerPage;
        $cart = $this->query();
        if (!empty($filter['user_id'])) {
            $cart->where('user_id', 'LIKE', '%'.$filter['user_id'].'%');
        }
        $total = $cart->count();
        $sort = $sort ?: 'id DESC';
        $list = $cart->skip($skip)->take($itemPerPage)->orderByRaw($sort)->get();
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
