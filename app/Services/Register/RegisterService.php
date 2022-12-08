<?php

namespace App\Services\Register;

use App\Models\User\UserModel;
use App\Repositories\User\UserRepository;
use App\Validation\RegisterValidation;

class RegisterService
{
    private UserModel $user;
    private string $passwordRepeat;

    public function __construct(UserModel $user, string $passwordRepeat)
    {
        $this->user = $user;
        $this->passwordRepeat = $passwordRepeat;
    }

    public function complete(): bool
    {
        $validation = new RegisterValidation(
            $this->user->getEmail(),
            $this->user->getPassword(),
            $this->passwordRepeat
        );

        if ($validation->success()) {
            $userRepository = new UserRepository($this->user);
            $userRepository->storeUser();
            return true;
        }
        return false;
    }
}