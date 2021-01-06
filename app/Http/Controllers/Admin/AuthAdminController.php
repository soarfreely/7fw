<?php
namespace App\Http\Controllers\Admin;

use App\Models\AdminModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;


class AuthAdminController extends AdminController
{
    /**
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

        Log::info('credentials', $credentials);

        AdminModel::query()->get();

        if (!$token = $this->guard->attempt($credentials)) {
            Log::info('error',  $credentials);
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * @return JsonResponse
     */
    public function me()
    {
        $data = $this->guard->user();
        $data['request_id'] = REQUEST_ID;

        return response()->json($data);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        $this->guard->logout();

        return response()->json([
            'message' => 'Successfully logged out',
            'request_id' => REQUEST_ID,
        ]);
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
            'expires_in' => $this->guard->factory()->getTTL() * 60,
            'request_id' => REQUEST_ID,
        ]);
    }


}
