<?php

namespace App\Validation;

class UserStockAmountValidation implements ValidationInterface
{
    private $userStockAmount;

    public function __construct($userStockAmount)
    {
        $this->userStockAmount = $userStockAmount;
    }

    public function success():bool
    {
        if ((int)$this->userStockAmount - (int)$_POST['sell'] < 0) {
            return false;
        }
        return true;
    }
}