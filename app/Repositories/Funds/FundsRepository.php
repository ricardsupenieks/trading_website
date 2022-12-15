<?php

namespace App\Repositories\Funds;

interface FundsRepository
{
    public function getFunds();
    public function updateFunds($amount);
}