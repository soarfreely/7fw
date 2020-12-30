<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{

    /**
     * 设置日期时间格式
     *
     * @var string
     */
    public $dateFormat = 'U';

    /**
     * 需要被转换日期时间格式的字段
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * 序列化
     *
     * @var array
     */
//    protected $casts = [
//        'created_at' => 'integer',
//        'updated_at' => 'integer',
//        'deleted_at' => 'integer'
//    ];

}
