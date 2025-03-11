<?php

namespace App\Models;

use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderModel extends Model implements CrudInterface
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'address_id',
        'address',
        'total_price',
        'status',
        'invoice_number',
    ];
    protected $attributes = [
        'status' => 'waiting_payment'
    ];
    protected $table = 'orders';

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }
    public function address()
    {
        return $this->belongsTo(AddressModel::class, 'address_id', 'id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItemModel::class, 'order_id', 'id');
    }

    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
    {
        $skip = ($page * $itemPerPage) - $itemPerPage;
        $order = $this->query();
        if (!empty($filter['name'])) {
            $order->whereHas('user', function ($query) use ($filter) {
                $query->where('name', 'LIKE', '%' . $filter['name'] . '%');
            })->with('user');
        }
        if (!empty($filter['user_id'])) {
            $order->where('user_id', 'LIKE', '%'.$filter['user_id'].'%');
        }
        if (!empty($filter['address_id'])) {
            $order->where('address_id', 'LIKE', '%'.$filter['address_id'].'%');
        }
        if (!empty($filter['status'])) {
            $order->where('status', 'LIKE', '%'.$filter['status'].'%');
        }
        if (!empty($filter['invoice_number'])) {
            $order->where('invoice_number', 'LIKE', '%'.$filter['invoice_number'].'%');
        }

        $total = $order->count();
        $sort = $sort ?: 'id DESC';
        $list = $order->skip($skip)->take($itemPerPage)->orderByRaw($sort)->get();
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
