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

        return new Template('inspect.twig', ['stock' => $stock]);
    }

    public function execute()
    {
        $stockService = new StockService();

        $fundsService = new FundsService();
        $funds = $fundsService->getFunds();

        $userStock = $stockService->getUserStock($_SESSION['stockId']);

        $userStockAmountValidation = new UserStockAmountValidation($userStock->getAmount());
        if (!$userStockAmountValidation->success()) {
            $_POST['sell'] = $userStock->getAmount();
        }

        $stock = $stockService->getStock($userStock->getSymbol());

        $totalFunds = $funds + ($stock->getPrice() * (int)$_POST['sell']) - ($stock->getPrice() * (int)$_POST['buy']);

        $fundsValidation = new FundsValidation($totalFunds);
        if(!$fundsValidation->success()) {
            $_SESSION['errors']['insufficientFunds'] = true;

            return new Redirect('/inspect');
        }

        $totalAmount = $userStock->getAmount() + $_POST['buy'] - $_POST['sell'];

        $fundsService->updateFunds($totalFunds);
        $stockService->updateStock($totalAmount, $_SESSION['stockId']);

        $transactionService = new TransactionService();

        if ($_POST['sell'] !== "") {
            $sellProfit = $stock->getPrice() * $_POST['sell'] - $userStock->getPrice() * $_POST['sell'];

            $transactionService->sellTransaction($stock, $sellProfit);
        }

        if ($_POST['buy'] !== "") {
            $buyProfit = $stock->getPrice() * $_POST['buy'] - $userStock->getPrice() * $_POST['buy'];

            $transactionService->buyTransaction($stock, $buyProfit);
        }

        return new Redirect('/');
    }
}