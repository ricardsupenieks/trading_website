<?php

namespace App\Repositories\User;

use App\Models\User\UserModel;

interface UserRepository
{
    public function storeUser(UserModel $user): void;
    public function getUser(string $email): string;

}