<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;


class AuthAdminController extends AdminController
{
    /**
     * 参考文档
     * https://learnku.com/articles/17883
     *
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth:guard_admin', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = $this->guard->attempt($credentials)) {
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
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        $this->guard->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard->refresh());
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


}
