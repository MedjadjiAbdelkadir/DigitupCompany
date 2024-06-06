<?php

namespace App\Http\Controllers\Api\Auth;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\User\UserResource;
use App\Repositories\User\UserRepository;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    private $users;

    /**
     * AuthController constructor.
     * @param UserRepository $users
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = $this->users->create($request->all());
            $token = $user->createToken('accessToken')->plainTextToken;

            return response()->json([
                'message' => 'User registered successfully',
                'token' => $token,
                'data' => new UserResource($user)
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function login(LoginRequest $request)
    {
        try {

            $user = $this->users->findByEmail($request->email);

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Invalid login details'
                ], 401);
            }
            $token = $user->createToken('accessToken')->plainTextToken;
            // $token = $user->createToken('accessToken')->plainTextToken;

            return response()->json([
                'message' => 'success',
                'token' => $token,
                'token_type' => 'Bearer',
                'data' => new UserResource($user),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            $user->update(['fcm_token' => null]);
            $user->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
