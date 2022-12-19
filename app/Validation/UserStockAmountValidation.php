<?php

namespace App\Validation;

class UserStockAmountValidation implements ValidationInterface
{
    private int $userStockAmount;
    private string $amount;

    public function __construct($amount, $userStockAmount)
    {
        $this->userStockAmount = $userStockAmount;
        $this->amount = $amount;
    }

    public function success():bool
    {
        if ($this->userStockAmount - (int)$this->amount < 0) {
            return false;
        }
        return true;
    }
}