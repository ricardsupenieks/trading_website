<?php

namespace App\Controllers;

use App\Models\User\UserModel;
use App\Redirect;
use App\Services\Register\RegisterService;
use App\Template;

class RegisterController
{
    public function showForm(): Template
    {
        return new Template('register.twig');
    }

    public function execute(): Redirect
    {
        $userCredentials = new UserModel(
            null,
            $_POST['name'],
            $_POST['email'],
            $_POST['password']
        );

        $registerService = new RegisterService($userCredentials, $_POST['password_repeat']);

        if ($registerService->complete()) {
            return new Redirect('/sign-in');
        }
        return new Redirect('/register');
    }

}