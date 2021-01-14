<?php


namespace App\Http\Controllers\Member;


use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Laravel\Lumen\Application;

class MemberController extends Controller
{
    /**
     * @var Factory|Guard|StatefulGuard|Application|null
     */
    protected $guard = null;

    /**
     * MemberController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->guard = auth('guard_member');
    }
}
