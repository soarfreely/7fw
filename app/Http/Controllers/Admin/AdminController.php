<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Laravel\Lumen\Application;

/**
 * 管理后台－基础控制器
 *
 * Class AdminController
 * @package App\Http\Controllers\Admin
 */
class AdminController extends Controller
{
    /**
     * @var Factory|Guard|StatefulGuard|Application|null
     */
    protected $guard = null;

    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->guard = auth('guard_admin');
    }
}
