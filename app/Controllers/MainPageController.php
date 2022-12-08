<?php

namespace App\Controllers;

use App\Template;

class MainPageController
{
    public function index(): Template
    {
        return new Template('main.twig');
    }
}