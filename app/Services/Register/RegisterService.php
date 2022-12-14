<?php

namespace App\Services\Register;

use App\Models\User\UserModel;
use App\Repositories\User\DatabaseUserRepository;

class RegisterService
{
    private UserModel $user;

    public function __construct(UserModel $user)
    {
        $this->user = $user;
    }

    public function execute(): void
    {
        $userRepository = new DatabaseUserRepository($this->user);
        $userRepository->storeUser();
    }
}