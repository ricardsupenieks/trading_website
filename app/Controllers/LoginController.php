<?php

namespace App\Controllers;

use App\Models\User\UserModel;
use App\Redirect;
use App\Services\Login\LoginService;
use App\Template;
use App\Validation\LoginValidation;

class LoginController
{
    public function showForm(): Template
    {
        return new Template('login.twig');
    }

    public function execute(): Redirect
    {
        $userCredentials = new UserModel(null,null, $_POST['email'], $_POST['password']);

        $loginValidation = new LoginValidation($userCredentials->getEmail(), $userCredentials->getPassword());

        if ($loginValidation->success()) {
            $loginService = new LoginService($userCredentials);

            $loginService->execute();

            return new Redirect('/');
        }

        unset($_SESSION['symbols']);

        return new Redirect('/sign-in');
    }
}