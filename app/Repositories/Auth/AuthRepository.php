
<?php
namespace App\Repositories\Auth;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthRepository extends BaseRepository implements AuthInterface
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->create($data);
        $user->access_token = $user->createToken('auth_token')->plainTextToken;
        $user->token_type = 'Bearer';
        return $user;
    }

    public function login(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $user->access_token = $user->createToken('auth_token')->plainTextToken;
            $user->token_type = 'Bearer';
            return $user;
        }
        return null;
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->tokens()->delete();
            Auth::logout();
            return true;
        }
        return false;
    }
}
