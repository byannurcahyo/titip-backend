<?php

namespace App\Models;

use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestSellerModel extends Model implements CrudInterface
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'status',
        'reviewed_at',
    ];
    protected $attributes = [
        'status' => 'pending'
    ];
    protected $table = 'request_sellers';

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }

    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
    {
        $skip = ($page * $itemPerPage) - $itemPerPage;
        $request = $this->query();
        if (!empty($filter['status'])) {
            $request->where('status', 'LIKE', '%'.$filter['status']);
        }
        if (!empty($filter['user_id'])) {
            $request->where('user_id', 'LIKE', '%'.$filter['user_id']);
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
        $requestSeller = $this->find($id);
        if ($requestSeller) {
            $requestSeller->update($payload);
            return $requestSeller;
        }
        return null;
    }

    public function drop(string $id)
    {
        return $this->find($id)->delete();
    }
}
