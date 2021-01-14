<?php

namespace App\Http\Middleware;

use Closure;
use App\Exceptions\Error;
use App\Models\AdminModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $guard
     * @return mixed
     * @throws Error
     */
    public function handle($request, Closure $next, $guard = null)
    {
//        dd($request);
        // mock admin user
        $adminMockUserId = empty($adminMockUserId) ? config('app_setting.admin.mock_user_id') : $adminMockUserId;

        if ($debug && config('app_setting.admin.mock_use') && !empty($adminMockUserId) && in_array(config('app.env'), ['dev', 'test'])) {
            // 查询管理员账号，不存在抛错：登录失效，请重新登录
            $admin = (new AdminModel())->query()->where(['admin_id' => $adminMockUserId])->first();
            if (empty($admin)) {
                throw new Error(200000);
            }
            // 往 request 中注入登录管理员数据
            $request->admin   = $admin;
            $request->adminId = $admin->admin_id;

            return $next($request);
        }

        // 检查登录凭证，抛错：登录失效，请重新登录
        if (!Auth::guard('guard_admin')->check()) {
            throw new Error(200000);
        }

        // 获取管理员信息
        $admin = Auth::guard()->user();
        // 往 request 中注入登录管理员数据
        $request->admin   = $admin;
        $request->adminId = $admin->admin_id;

        // 登录后，系统管理员在后台对登录管理员进行操作(重置密码，停用)后，登录管理员登出并抛错：登录失效，请重新登录
        if (($admin->last_edit_at > $admin->last_login_at) || AdminModel::OFF == $admin->status) {
            Auth::logout();
            throw new Error(200000);
        }

        return $next($request);
    }
}
