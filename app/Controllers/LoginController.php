<?php

namespace App\Controllers;

use App\Models\User\UserModel;
use App\Redirect;
use App\Services\Login\LoginService;
use App\Template;

class LoginController
{
    public function showForm(): Template
    {
        return new Template('login.twig');
    }

    public function execute(): Redirect
    {
        $userCredentials = new UserModel(null,null, $_POST['email'], $_POST['password']);

        $loginService = new LoginService($userCredentials);

        unset($_SESSION['symbols']);

        if ($loginService->complete()) {
            return new Redirect('/');
        }
        return new Redirect('/sign-in');
    }
}