<?php

namespace App\Services\Stock;

use App\Models\Stock\StockModel;
use App\Models\Stock\UserStockModel;
use App\Repositories\Stocks\ApiStocksRepository;
use App\Repositories\Stocks\DatabaseStocksRepository;


class StockService
{
    private ApiStocksRepository $apiStockRepository;
    private DatabaseStocksRepository $databaseStockRepository;

    public function __construct()
    {
        $this->apiStockRepository = new ApiStocksRepository();
        $this->databaseStockRepository = new DatabaseStocksRepository();
    }

    public function getStock(string $stockSymbol): StockModel
    {
        return $this->apiStockRepository->getStock($stockSymbol);
    }

    public function updateStock($amount): void
    {
        $this->databaseStockRepository->updateStock($amount);
    }

    public function getUserStock(): UserStockModel
    {
        return $this->databaseStockRepository->getStock();
    }

    public function saveStock(UserStockModel $stock): void
    {
        $this->databaseStockRepository->saveStock($stock);
    }


}