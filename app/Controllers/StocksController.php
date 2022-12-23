<?php

namespace App\Controllers;

use App\Models\Stock\StockModel;
use App\Redirect;
use App\Services\Funds\FundsService;
use App\Services\Stock\StockService;
use App\Services\Transactions\TransactionService;
use App\Session;
use App\Template;
use App\Validation\FundsValidation;
use App\Validation\StockValidation;
use App\Validation\UserStockAmountValidation;


class StocksController
{
    public function index(): Template
    {
        return new Template('main.twig');
    }


    public function search(): Template
    {
        if(!empty($_GET['query'])) {
            Session::store('searchTerm', $_GET['query']);
        }

        $stockValidation = new StockValidation($_SESSION['searchTerm']);

        if ($stockValidation->success()) {

            return new Template('search.twig', ['failed' => true]);
        }

        $stockService = new StockService();
        $stock = $stockService->getStock($_SESSION['searchTerm']);

        return new Template('search.twig', ['result' => $stock]);
    }


    public function add(): Redirect
    {
        $stockService = new StockService();
        $searchedStock = $stockService->getStock($_SESSION['searchTerm']);

        $fundsService = new FundsService();
        $funds = $fundsService->getFunds();
        $totalFunds = $funds - ((float)$searchedStock->getPrice() * (int)$_POST['amount']);

        $fundsValidation = new FundsValidation($totalFunds);
        if($fundsValidation->success() !== true) {
            $_SESSION['errors']['insufficientFunds'] = true;

            return new Redirect('/search');
        }

        $fundsService->updateFunds($totalFunds);

        $userStock = $stockService->getUserStockBySymbol($_SESSION['searchTerm'], $_SESSION['user']);

        if($userStock !== null) {
            $totalAmount = $userStock->getAmount() + $_POST['amount'];
            if ($userStock->getAmount() < 0 && $totalAmount > 0) {
                $_POST['amount'] = -$userStock->getAmount();
            }
        }

        $userStock = new StockModel(
            $searchedStock->getSymbol(),
            $searchedStock->getName(),
            $searchedStock->getPrice(),
            $searchedStock->getHighPrice()
        );

        $stockService->saveStock($userStock, $_SESSION['user'], $_POST['amount']);

        $profit =
            (float)$searchedStock->getHighPrice() * (int)$_POST['amount'] -
            (float)$searchedStock->getPrice() * (int)$_POST['amount'];

        $transactionService = new TransactionService();
        $transactionService->buyTransaction($searchedStock, $profit, $_POST['amount']);

        return new Redirect('/');
    }

    public function borrow(): Redirect
    {
        $stockService = new StockService();

        $userStock = $stockService->getUserStockBySymbol($_SESSION['searchTerm'], $_SESSION['user']);

        if($userStock !== null) {
            $stock = $stockService->getStock($_SESSION['searchTerm']);

            $fundsService = new FundsService();
            $funds = $fundsService->getFunds();

            $totalFunds = $funds + ($stock->getPrice() * (int)$_POST['amount']);

            if($userStock->getAmount() > 0) {
                $userStockAmountValidation = new UserStockAmountValidation($_POST['amount'],$userStock->getAmount());
                if (!$userStockAmountValidation->success()) {
                    $_POST['amount'] = $userStock->getAmount();
                    $stockService->updateStock(0, $userStock->getId(),0,$_POST['amount'], $stock);
                } else {
                    $stockService->shortStock($stock, $_SESSION['user'], -$_POST['amount']);
                }
            }

            $fundsService->updateFunds($totalFunds);

            $profit = (float)$stock->getPrice() * (int)$_POST['amount'] - $userStock->getAveragePrice() * (int)$_POST['amount'];

            $transactionService = new TransactionService();
            $transactionService->sellTransaction($stock, $profit, $_POST['amount']);

            return new Redirect('/');
        }

        $stock = $stockService->getStock($_SESSION['searchTerm']);

        $fundsService = new FundsService();
        $funds = $fundsService->getFunds();
        $totalFunds = $funds + ($stock->getPrice() * (int)$_POST['amount']);
        $fundsService->updateFunds($totalFunds);

        $userStock = new StockModel(
            $stock->getSymbol(),
            $stock->getName(),
            $stock->getPrice(),
            $stock->getHighPrice(),
        );

        $stockService->shortStock($userStock, $_SESSION['user'], -$_POST['amount']);

        $transactionService = new TransactionService();

        $profit = (float)$stock->getHighPrice() * (int)$_POST['amount'] - $stock->getPrice() * (int)$_POST['amount'];
        $transactionService->sellTransaction($stock, $profit, $_POST['amount']);

        return new Redirect('/');
    }
}