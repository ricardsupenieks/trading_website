<?php

namespace App\Services\Login;

use App\Models\User\UserModel;
use App\Repositories\User\DatabaseUserRepository;

class LoginService
{

    private UserModel $user;

    public function __construct(UserModel $user)
    {
        $this->user = $user;
    }

    public function execute(): void
    {
        $userRepository = new DatabaseUserRepository();
        $_SESSION['user'] = $userRepository->getUser($this->user->getEmail());
    }
}