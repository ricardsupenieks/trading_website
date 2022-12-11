<?php

namespace App\Controllers;

use App\Models\Stock\StockModel;
use App\Models\User\UserModel;
use App\Redirect;
use App\Services\Stock\StockService;
use App\Session;
use App\Template;


class StocksController
{
    public function index(): Template
    {
        $stockService = new StockService();
        $stocks = $stockService->getAllStocks($_SESSION['symbols'] ?? []);

        return new Template('main.twig', ['stocks' => $stocks->getStocks()]);
    }


    public function search(): Template
    {
        Session::store('search', $_GET['search']);
        $stockService = new StockService();
        $stock = $stockService->getStock($_SESSION['search']);

        return new Template('main.twig', ['result' => $stock]);
    }


    public function add(): Redirect
    {
        $stockService = new StockService();
        $add = $stockService->getStock($_SESSION['search']);
        Session::storeInArray('symbols', $add->getSymbol());
        unset($_SESSION['search']);

        return new Redirect('/');
    }

}