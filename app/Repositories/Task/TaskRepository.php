<?php
namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use App\Repositories\User\UserInterface;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class TaskRepository extends BaseRepository implements TaskInterface
{
    /**
     * Define the model for the repository.
     *
     * @return User
     */
    // protected function model()
    // {
    //     return new User();
    // }
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * Find a user by their email.
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }
}
