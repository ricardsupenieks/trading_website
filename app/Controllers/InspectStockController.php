<?php

namespace App\Controllers;

use App\Redirect;
use App\Services\Funds\FundsService;
use App\Services\Stock\StockService;
use App\Services\Transactions\TransactionService;
use App\Template;
use App\Validation\FundsValidation;
use App\Validation\UserStockAmountValidation;

class InspectStockController
{

    public function index()
    {
        $stockService = new StockService();

        if(!empty($_GET['stock'])) {
            $_SESSION['stockId'] = $_GET['stock'];
        }

        $userStock = $stockService->getUserStock($_SESSION['stockId']);

        $stock = $stockService->getStock($userStock->getSymbol());

        return new Template('inspect.twig', ['stock' => $stock, 'userStock' => $userStock]);
    }

    public function execute()
    {
        $stockService = new StockService();

        $fundsService = new FundsService();
        $funds = $fundsService->getFunds();

        $userStock = $stockService->getUserStock($_SESSION['stockId']);

        $totalAmount = $userStock->getAmount() - $_POST['sell'] + $_POST['buy'];
        if($totalAmount <= 0) {
            $_POST['sell'] = ($userStock->getAmount() + $_POST['buy']);
            $totalAmount = $userStock->getAmount() - $_POST['sell'] + $_POST['buy'];
        }

        $stock = $stockService->getStock($userStock->getSymbol());

        $totalFunds = $funds + ($stock->getPrice() * (int)$_POST['sell']) - ($stock->getPrice() * (int)$_POST['buy']);

        $fundsValidation = new FundsValidation($totalFunds);
        if(!$fundsValidation->success()) {
            $_SESSION['errors']['insufficientFunds'] = true;

            return new Redirect('/inspect');
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