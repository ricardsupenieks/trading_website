<?php

namespace App\Controllers;

use App\Redirect;
use App\Services\Stock\StockService;
use App\Template;
use App\Validation\LoginValidation;
use App\Validation\StockValidation;
use App\Validation\UserStockAmountValidation;
use App\Validation\UserValidation;

class TransferController
{
    public function showForm(): Template
    {
        return new Template('transfer.twig');
    }

    public function execute(): Redirect
    {
        $stockValidation = new StockValidation($_POST['symbol']);
        if ($stockValidation->success() === false) {
            $_SESSION['errors']['incorrectStockSymbol'] = true;

            return new Redirect('/transfer');
        }

        $userValidation = new UserValidation($_POST['email']);
        if ($userValidation->success() === false) {
            $_SESSION['errors']['incorrectTransferEmail'] = true;

            return new Redirect('/transfer');
        }

        $passwordValidation = new LoginValidation(null, $_POST['password']);
        if ($passwordValidation->checkPassword() === false) {
            $_SESSION['errors']['incorrectPassword'] = true;

            return new Redirect('/transfer');
        }

        $stockService = new StockService();
        $stockService->
        $userStock = $stockService->getUserStock();


        $userStockAmountValidation = new UserStockAmountValidation($userStock->getAmount());
        if (!$userStockAmountValidation->success()) {
            $_POST['sell'] = $userStock->getAmount();
        }

        return new Redirect('/transfer');
    }
}