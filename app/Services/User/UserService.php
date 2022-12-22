<?php

namespace App\Services\User;

use App\Repositories\User\DatabaseUserRepository;

class UserService
{
    private DatabaseUserRepository $databaseUserRepository;

    public function __construct()
    {
        $this->databaseUserRepository = new DatabaseUserRepository();
    }


    public function getUserId($email):string
    {
        return $this->databaseUserRepository->getUser($email);
    }

}