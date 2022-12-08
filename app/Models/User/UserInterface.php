<?php

namespace App\Models\User;

interface UserInterface
{
    public function getId();
    public function getName();
    public function getEmail();
    public function getPassword();
}