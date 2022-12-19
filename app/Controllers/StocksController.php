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


class StocksController
{
    public function index(): Template
    {
        return new Template('main.twig');
    }


    public function search(): Template
    {
        Session::store('searchTerm', $_GET['query']);

        $stockValidation = new StockValidation($_SESSION['searchTerm']);

        if (!$stockValidation->success()) {

            return new Template('search.twig', ['failed' => true]);
        }

        $stockService = new StockService();
        $stock = $stockService->getStock($_SESSION['searchTerm']);

        return new Template('search.twig', ['result' => $stock]);
    }


    public function add(): Redirect
    {
        $stockService = new StockService();
        $stock = $stockService->getStock($_SESSION['searchTerm']);

        $fundsService = new FundsService();
        $funds = $fundsService->getFunds();
        $totalFunds = $funds - ((float)$stock->getPrice() * (int)$_POST['amount']);

        $fundsValidation = new FundsValidation($totalFunds);
        if($fundsValidation->success() !== true) {
            $_SESSION['errors']['insufficientFunds'] = true;

            return new Redirect('/search');
        }

        $fundsService->updateFunds($totalFunds);

        $userStock = new StockModel(
            $stock->getSymbol(),
            $stock->getName(),
            $stock->getPrice(),
            $stock->getHighPrice()
        );

        $stockService->saveStock($userStock, $_SESSION['user'], $_POST['amount']);

        $profit = (float)$stock->getHighPrice() * (int)$_POST['amount'] - (float)$stock->getPrice() * (int)$_POST['amount'];

        $transactionService = new TransactionService();
        $transactionService->buyTransaction($stock, $profit, $_POST['amount']);

        return new Redirect('/');
    }
}