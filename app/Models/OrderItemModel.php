<?php

namespace App\Models;

use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItemModel extends Model implements CrudInterface
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;
    public $timestamps = true;
    protected $fillable = [
        'order_id',
        'seller_id',
        'product_id',
        'product_name',
        'price',
        'description',
        'quantity',
        'subTotal',
    ];
    protected $table = 'order_items';

    public function order()
    {
        return $this->belongsTo(OrderModel::class, 'order_id', 'id');
    }
    public function seller()
    {
        return $this->belongsTo(SellerModel::class, 'seller_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'product_id', 'id');
    }

    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
    {
        $skip = ($page * $itemPerPage) - $itemPerPage;
        $orderItem = $this->query();
        if (!empty($filter['order_id'])) {
            $orderItem->where('order_id', 'LIKE', '%'.$filter['order_id'].'%');
        }
        if (!empty($filter['seller_id'])) {
            $orderItem->where('seller_id', 'LIKE', '%'.$filter['seller_id'].'%');
        }
        if (!empty($filter['product_id'])) {
            $orderItem->where('product_id', 'LIKE', '%'.$filter['product_id'].'%');
        }
        $total = $orderItem->count();
        $sort = $sort ?: 'id DESC';
        $list = $orderItem->skip($skip)->take($itemPerPage)->orderByRaw($sort)->get();
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
