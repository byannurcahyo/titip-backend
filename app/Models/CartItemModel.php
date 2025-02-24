<?php

namespace App\Models;

use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItemModel extends Model implements CrudInterface
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;
    public $timestamps = true;
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
    ];
    protected $table = 'cart_items';

    public function cart()
    {
        return $this->belongsTo(CartModel::class, 'cart_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'product_id', 'id');
    }

    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
    {
        $skip = ($page * $itemPerPage) - $itemPerPage;
        $cartItem = $this->query();
        if (!empty($filter['cart_id'])) {
            $cartItem->where('cart_id', 'LIKE', '%'.$filter['cart_id'].'%');
        }
        if (!empty($filter['product_id'])) {
            $cartItem->where('product_id', 'LIKE', '%'.$filter['product_id'].'%');
        }
        $total = $cartItem->count();
        $sort = $sort ?: 'id DESC';
        $list = $cartItem->skip($skip)->take($itemPerPage)->orderByRaw($sort)->get();
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
