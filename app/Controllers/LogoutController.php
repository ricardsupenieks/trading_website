<?php

namespace App\Controllers;

use App\Redirect;

class LogoutController
{
    public function execute(): Redirect
    {
        session_unset();
        return new Redirect("/");
    }
}