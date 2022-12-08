<?php

namespace App\Validation;

interface ValidationInterface
{
    public function checkEmail();
    public function checkPassword();
    public function success();
}