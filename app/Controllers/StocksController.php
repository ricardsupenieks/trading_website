<?php

namespace App\Controllers;

use App\Database;
use App\Redirect;
use App\Services\Stock\StockService;
use App\Session;
use App\Template;


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

        if ($_SESSION['searchTerm'] !== null) {
            $stock = $stockService->getStock($_SESSION['searchTerm']);
        } else {
            $stock = $stockService->getStock($_SESSION['search']);
        }

        Session::store('search', $_SESSION['searchTerm']);

        if ($stock->getPrice() == 0 && $stock->getPriceChange() == null) {
            $failed = true;

            return new Template('search.twig', ['failed' => $failed]);
        }

        return new Template('search.twig', ['result' => $stock]);
    }


    public function add(): Redirect
    {
        $stockService = new StockService();

        $stock = $stockService->getStock($_SESSION['search']);

        $connection = Database::getConnection();

        $queryBuilder = $connection->createQueryBuilder();

        $funds = $queryBuilder
            ->select('money')
            ->from('users')
            ->where('id = ?')
            ->setParameter(0, $_SESSION['user'])
            ->fetchOne();

        $totalFunds = (float)$funds - ((float)$stock->getPrice() * (int)$_POST['amount']);

        if($totalFunds < 0) {
            $_SESSION['errors']['insufficientFunds'] = true;

            return new Redirect('/search');
        }

        $connection->update('`stocks-api`.users', ['money' => $totalFunds], ['id' => $_SESSION['user']]);

        $connection->insert('`stocks-api`.stocks', [
            'symbol' => $stock->getSymbol(),
            'name' => $stock->getName(),
            'amount' => $_POST['amount'],
            'price' => $stock->getPrice(),
            'owner_id' => $_SESSION['user'],
        ]);

        return new Redirect('/');
    }

}