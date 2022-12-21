<?php

namespace App\Controllers;

use App\Redirect;
use App\Services\Funds\FundsService;
use App\Services\Stock\StockService;
use App\Services\Transactions\TransactionService;
use App\Template;
use App\Validation\FundsValidation;
use App\Validation\UserStockAmountValidation;

class ShortController
{
    public function index()
    {
        $stockService = new StockService();

        if(!empty($_GET['stock'])) {
            $_SESSION['stockId'] = $_GET['stock'];
        }

        $userStock = $stockService->getUserStock($_SESSION['stockId']);

        $stock = $stockService->getStock($userStock->getSymbol());

        return new Template('short.twig', ['stock' => $stock, 'userStock' => $userStock]);
    }

    public function execute()
    {
        $stockService = new StockService();
        $userStock = $stockService->getUserStock($_SESSION['stockId']);

        $totalAmount = $userStock->getAmount() - $_POST['sell'] + $_POST['buy'];
        if($totalAmount > 0) {
            $_POST['buy'] = -1*($userStock->getAmount() - $_POST['sell']);
            $totalAmount = $userStock->getAmount() - $_POST['sell'] + $_POST['buy'];
;
        }
        $stock = $stockService->getStock($userStock->getSymbol());

        $fundsService = new FundsService();
        $funds = $fundsService->getFunds();

        $totalFunds = $funds + ($stock->getPrice() * (int)$_POST['sell']) - ($stock->getPrice() * (int)$_POST['buy']);

        $fundsValidation = new FundsValidation($totalFunds);
        if(!$fundsValidation->success()) {
            $_SESSION['errors']['insufficientFunds'] = true;

            return new Redirect('/short');
        }

        $fundsService->updateFunds($totalFunds);
        $stockService->updateStock($totalAmount, $_SESSION['stockId']);

        $transactionService = new TransactionService();

        if ($_POST['sell'] !== "") {
            $sellProfit = (float)$stock->getPrice() * (int)$_POST['sell'] - $userStock->getPrice() * (int)$_POST['sell'];

            $transactionService->sellTransaction($stock, $sellProfit, $_POST['sell']);
        }

        if ($_POST['buy'] !== "") {
            $buyProfit = (float)$stock->getHighPrice() * (int)$_POST['buy'] - (float)$stock->getPrice() * (int)$_POST['buy'];


            $transactionService->buyTransaction($stock, $buyProfit, $_POST['buy']);
        }

        return new Redirect('/');
    }
}