<?php

namespace App\Controllers;

use App\Services\Stock\StockService;
use App\Template;


class StocksController
{
    public function index(): Template
    {
        $stockSymbols = [
            "AAPL",
            "EDESY",
            "NSCIF",
            "NODB",
            "SEAV",
            "CSSEL",
            "XNET",
            "TRCA",
            "SNDD",
            "SRKZF",
        ];

        $stockService = new StockService();
        $stocks = $stockService->execute($stockSymbols);

//        if ($stocks !== null) {
//            var_dump($stocks);
//            die;
//        }
        return new Template('main.twig', ['stocks' => $stocks->getStocks()]);
    }
}