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

    public function updateStock($totalAmount, $userStockId, $buyAmount, $sellAmount, $stock): void
    {
        $this->databaseStockRepository->updateStock($totalAmount, $userStockId, $buyAmount, $sellAmount, $stock);
    }

    public function getUserStock($stockId): UserStockModel
    {
        return $this->databaseStockRepository->getStock($stockId);
    }

    public function getUserStockBySymbol($symbol, $userId)
    {
        return $this->databaseStockRepository->getStockBySymbol($symbol, $userId);
    }

    public function saveStock(StockModel $stock, $ownerId, $amount): void
    {
        $this->databaseStockRepository->saveStock($stock,$ownerId,$amount);
    }

    public function shortStock($stock, $ownerId, $amount): void
    {
        $this->databaseStockRepository->borrowStock($stock,$ownerId,$amount);
    }
}