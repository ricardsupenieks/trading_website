<?php

namespace App\Services\Stock;

use App\Models\Collections\StockCollection;
use App\Repositories\Stocks\ApiStocksRepository;


class StockService
{
    public function execute(array $stockSymbols): StockCollection
    {
        $stocks = [];
        foreach ($stockSymbols as $stockSymbol) {
            $stockRepository = new ApiStocksRepository();
            $stocks []= $stockRepository->getStock($stockSymbol);
        }
        $stockCollection = new StockCollection($stocks);
        return $stockCollection;
    }
}