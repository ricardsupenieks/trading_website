<?php

namespace App\Validation;

class FundsValidation implements ValidationInterface
{
    private float $totalFunds;

    public function __construct($totalFunds)
    {
        $this->totalFunds = $totalFunds;
    }

    public function success(): bool
    {
        if ($this->totalFunds < 0) {
            return false;
        }

        return true;
    }
}