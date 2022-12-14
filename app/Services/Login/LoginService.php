<?php

namespace App\Services\Login;

use App\Models\User\UserModel;
use App\Repositories\User\DatabaseUserRepository;
use App\Validation\LoginValidation;

class LoginService
{

    private UserModel $user;

    public function __construct(UserModel $user)
    {
        $this->user = $user;
    }

    public function execute(): void
    {
        $userRepository = new DatabaseUserRepository($this->user);
        $_SESSION['user'] = $userRepository->getUser();
    }
}