
<?php
namespace App\Repositories\Auth;
use App\Repositories\BaseInterface;

interface AuthInterface extends BaseInterface
{
    public function register(array $data);
    public function login(array $credentials);
    public function logout();
}
