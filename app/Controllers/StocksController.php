<?php

namespace App\Controllers;

use App\Models\Stock\UserStockModel;
use App\Redirect;
use App\Services\Funds\FundsService;
use App\Services\Stock\StockService;
use App\Services\Transactions\TransactionService;
use App\Session;
use App\Template;
use App\Validation\FundsValidation;


class StocksController
{
    public function index(): Template
    {
        return new Template('main.twig');
    }


    public function search(): Template
    {
        Session::store('searchTerm', $_GET['query']);

        $stockService = new StockService();
            $stock = $stockService->getStock($_SESSION['searchTerm']);


        if ($stock->getPrice() == 0 && $stock->getPriceChange() == null) {

            return new Template('search.twig', ['failed' => true]);
        }

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

        $userStock = new UserStockModel(
            $stock->getSymbol(),
            $stock->getName(),
            $_POST['amount'],
            $stock->getPrice(),
            $_SESSION['user']
        );

        $stockService->saveStock($userStock);

        $profit = (float)$stock->getHighPrice() * $_POST['amount'] - (float)$stock->getPrice() * $_POST['amount'];

        $transactionService = new TransactionService();
        $transactionService->buyTransaction($stock, $profit);

        return new Redirect('/');
    }
}