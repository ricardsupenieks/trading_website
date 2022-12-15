<?php

namespace App\Controllers;

use App\Redirect;
use App\Services\Funds\FundsService;
use App\Validation\FundsValidation;

class FundsController
{
    public function depositWithdraw(): Redirect
    {
        $fundsService = new FundsService();

        $funds = $fundsService->getFunds();

        $totalFunds = floatval($_POST['deposit']) + $funds - floatval($_POST['withdraw']);
        $fundsValidation = new FundsValidation($totalFunds);
        if ($fundsValidation->success()) {
            $fundsService->updateFunds($totalFunds);

            return new Redirect('/profile');
        }

        $_SESSION['errors']['insufficientFundsInWallet'] = true;

        return new Redirect('/profile');
    }
}