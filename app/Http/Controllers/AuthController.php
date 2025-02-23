<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Repositories\User\UserInterface;
use App\Repositories\Auth\AuthInterface;

class AuthController extends Controller
{
    protected $authRepository;
    protected $userRepository;

    public function __construct(AuthInterface $authRepository, UserInterface $userRepository)
    {
        $this->authRepository = $authRepository;
        $this->userRepository = $userRepository;
    }

    public function register(Request $request)
    {
        try {
            $validationResponse = $this->validateRequest($request);
            if ($validationResponse !== null) {
                return $validationResponse;
            }
            
            $data = $request->all();
            $data['password'] = Hash::make($data['password']);
            
            $user = $this->userRepository->create($data);
            $token = $user->createToken('auth_token')->plainTextToken;
            
            $user->access_token = $token;
            $user->token_type = 'Bearer';
            
            return $user;
            
        } catch (Exception $e) {
            return catchErrorResponse($e);
        }
    }

    public function login(Request $request)
    {
        try {
            $validationResponse = $this->validateRequest($request);
            if ($validationResponse !== null) {
                return $validationResponse;
            }
            
            $credentials = $request->only(['email', 'password']);
            $user = $this->authRepository->login($credentials);
            
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }
            
            return $user;
            
        } catch (Exception $e) {
            return catchErrorResponse($e);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
