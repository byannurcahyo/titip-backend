<?php

namespace App\Models;

use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductModel extends Model implements CrudInterface
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;
    public $timestamps = true;
    protected $fillable = [
        'seller_id',
        'name',
        'description',
        'price',
        'stock',
        'photo',
    ];
    protected $table = 'products';

    public function seller()
    {
        return $this->belongsTo(SellerModel::class, 'seller_id', 'id');
    }
    public function cartItems()
    {
        return $this->hasMany(CartItemModel::class, 'product_id', 'id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItemModel::class, 'product_id', 'id');
    }

    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
    {
        $skip = ($page * $itemPerPage) - $itemPerPage;
        $product = $this->query();
        if (!empty($filter['name'])) {
            $product->where('name', 'LIKE', '%'.$filter['name'].'%');
        }
        if (!empty($filter['seller_id'])) {
            $product->where('seller_id', 'LIKE', '%'.$filter['seller_id'].'%');
        }
        $total = $product->count();
        $sort = $sort ?: 'id DESC';
        $list = $product->skip($skip)->take($itemPerPage)->orderByRaw($sort)->get();
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
