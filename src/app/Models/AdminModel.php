<?php

namespace App\Models;


use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class AdminModel extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable;

    /**
     * 表名
     *
     * @var string
     */
    protected $table = 'admin';

    /**
     * 白名单
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password'
    ];

    /**
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array|string[]
     */
    public function getJWTCustomClaims()
    {
        return [
            'role' => 'admin',
            'customize' => 'gavin',
        ];
    }
}
