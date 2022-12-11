<?php

namespace App\Services\Stock;

use App\Models\Collections\StockCollection;
use App\Models\Stock\StockModel;
use App\Repositories\Stocks\ApiStocksRepository;


class StockService
{
    public function __construct()
    {
        $this->stockRepository = new ApiStocksRepository();
    }

    public function getAllStocks(array $stockSymbols): StockCollection
    {
        $stocks = [];
        foreach ($stockSymbols as $stockSymbol) {
            $stocks []= $this->stockRepository->getStock($stockSymbol);
        }
        $stockCollection = new StockCollection($stocks);
        return $stockCollection;
    }

    public function getStock(string $stockSymbol): StockModel
    {
        return $this->stockRepository->getStock($stockSymbol);
    }
}