<?php

namespace App\Repositories\User;

interface UserRepository
{
    public function storeUser(): void;
    public function getUser(): string;

}