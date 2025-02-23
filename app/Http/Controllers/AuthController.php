
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Repositories\User\UserInterface;

class AuthController extends Controller
{
    protected $userRepository;

    public function __construct(UserInterface $userRepository)
    {
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
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($validated)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
