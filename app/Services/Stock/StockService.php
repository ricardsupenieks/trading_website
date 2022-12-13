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

    public function getStock(string $stockSymbol): StockModel
    {
        return $this->stockRepository->getStock($stockSymbol);
    }
}