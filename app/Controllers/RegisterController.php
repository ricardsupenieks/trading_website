<?php

namespace App\Controllers;

use App\Models\User\UserModel;
use App\Redirect;
use App\Services\Register\RegisterService;
use App\Template;
use App\Validation\RegisterValidation;

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



        $validation = new RegisterValidation(
            $userCredentials->getEmail(),
            $userCredentials->getPassword(),
            $_POST['password_repeat'],
        );

        if ($validation->success()) {
            $registerService = new RegisterService($userCredentials);
            $registerService->execute();

            return new Redirect('/sign-in');
        }

        return new Redirect('/register');
    }

}