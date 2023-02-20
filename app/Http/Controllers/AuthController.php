<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUser;
use App\Http\Requests\RegisterUser;
use App\Models\User;
use JWTAuth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterUser $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create(array_merge(
                $request->all(),
                ['password' => bcrypt($request->password)]
            ));
            DB::commit();
            return api_response_send('success', 'User created successfully.', $user, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return api_response_send('error', $e->getMessage(), [], 500);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginUser $request)
    {
        if (!$token = JWTAuth::attempt($request->all())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $auth = $this->createNewToken($token);
        if ($auth) {
            return api_response_send('success', 'User Loggedin successfully.', $auth, 200);
        } else {
            return api_response_send('error', 'Your email/password combination was incorrect.', '', 401);
        }
    }

    protected function createNewToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth()->user()
        ];
    }
}
