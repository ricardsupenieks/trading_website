<?php

namespace App\Repositories\Transactions;

use App\Models\Stock\StockModel;

interface TransactionsRepository
{
    public function createSellTransaction(StockModel $stock, $profit);
    public function createBuyTransaction(StockModel $stock, $profit);

}