<?php

namespace App\Models;

use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class UserModel extends Authenticatable implements CrudInterface, JWTSubject
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;
    public $timestamps = true;
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone_number',
        'photo',
        'google_id',
        'updated_security',
    ];
    protected $attributes = [
        'role' => 'user'
    ];
    protected $table = 'users';

    public function requestSeller()
    {
        return $this->hasOne( RequestSellerModel::class, 'user_id', 'id');
    }
    public function seller()
    {
        return $this->hasOne( SellerModel::class, 'user_id', 'id');
    }
    public function cart()
    {
        return $this->hasOne( CartModel::class, 'user_id', 'id');
    }
    public function order()
    {
        return $this->hasMany( OrderModel::class, 'user_id', 'id');
    }
    public function address()
    {
        return $this->hasMany( AddressModel::class, 'user_id', 'id');
    }
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Payload yang disimpan pada token JWT, jangan tampilkan informasi
     * yang bersifat rahasia pada payload ini untuk mengamankan akun pengguna
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'user' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
                'updated_security' => $this->updated_security,
            ],
        ];
    }

    /**
     * Method untuk mengecek apakah user memiliki permission
     *
     * @param  string  $permissionName  contoh: user.create / user.update
     * @return bool
     */
    public function isHasRole($permissionName)
    {
        $tokenPermission = explode('|', $permissionName);
        $userPrivilege = json_decode($this->role->access ?? '{}', true);

        foreach ($tokenPermission as $val) {
            $permission = explode('.', $val);
            $feature = $permission[0] ?? '-';
            $activity = $permission[1] ?? '-';
            if (isset($userPrivilege[$feature][$activity]) && $userPrivilege[$feature][$activity] === true) {
                return true;
            }
        }

        return false;
    }

    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
    {
        $skip = ($page * $itemPerPage) - $itemPerPage;
        $request = $this->query();
        if (!empty($filter['name'])) {
            $request->where('name', 'LIKE', '%'.$filter['name'].'%');
        }
        if (!empty($filter['email'])) {
            $request->where('email', 'LIKE', '%'.$filter['email'].'%');
        }
        $total = $request->count();
        $sort = $sort ?: 'name ASC, email ASC';
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
        return $this->find($id)->update($payload);
    }

    public function drop(string $id)
    {
        return $this->find($id)->delete();
    }
}
