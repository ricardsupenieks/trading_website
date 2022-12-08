<?php

namespace App\Services\Login;

use App\Models\User\UserModel;
use App\Repositories\User\UserRepository;
use App\Validation\LoginValidation;

class LoginService
{

    private UserModel $user;

    public function __construct(UserModel $user)
    {
        $this->user = $user;
    }

    public function complete(): bool
    {
        $loginValidation = new LoginValidation($this->user->getEmail(), $this->user->getPassword());

        if ($loginValidation->success())
        {
            $userRepository = new UserRepository($this->user);
            $_SESSION['user'] = $userRepository->getUser();
            return true;
        }
        return false;
    }
}