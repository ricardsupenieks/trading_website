<?php

namespace App\Repositories\Transactions;

use App\Models\Stock\StockModel;

interface TransactionsRepository
{
    public function createSellTransaction(StockModel $stock, $profit, $amount);
    public function createBuyTransaction(StockModel $stock, $profit, $amount);

}