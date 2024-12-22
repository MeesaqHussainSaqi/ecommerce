<?php
namespace App\Repositories\User;
use App\Repositories\BaseInterface;

interface UserInterface extends BaseInterface
{
    public function findByEmail(string $email);
}