<?php

namespace App\Controllers;

use App\Redirect;
use App\Template;

class TransferController
{
    public function showForm(): Template
    {
        return new Template('transfer.twig');
    }

//    public function execute(): Redirect
//    {
//
//    }
}