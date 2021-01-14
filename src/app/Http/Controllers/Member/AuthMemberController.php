<?php

namespace App\Http\Controllers\Member;


use Illuminate\Http\JsonResponse;

class AuthMemberController extends MemberController
{
    /**
     * AuthMemberController constructor.
     */
    public function __construct()
    {
        parent::__construct();

//        $this->middleware('auth:guard_member', ['except' => ['login']]);
    }

    /**
     * Member　登录
     *
     * @return JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = $this->guard->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * @return JsonResponse
     */
    public function me()
    {
        return response()->json($this->guard->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard->factory()->getTTL() * 60
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        $this->guard->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
