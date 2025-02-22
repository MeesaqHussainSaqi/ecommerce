<?php
namespace App\Repositories\User;
use App\Repositories\BaseInterface;

interface TaskInterface extends BaseInterface
{
    public function findByEmail(string $email);
}