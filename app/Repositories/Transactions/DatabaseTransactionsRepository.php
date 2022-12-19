<?php

namespace App\Repositories\Transactions;

use App\Database;
use App\Models\Stock\StockModel;
use Doctrine\DBAL\Connection;

class DatabaseTransactionsRepository implements TransactionsRepository
{
    private Connection $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function createSellTransaction(StockModel $stock, $profit, $amount)
    {
        $this->connection->insert('`stocks-api`.transactions', [
            'symbol' => $stock->getSymbol(),
            'amount' => $amount,
            'action' => 'sell',
            'profit' => $profit,
            'owner_id' => $_SESSION['user'],
            ]);
    }

    public function createBuyTransaction(StockModel $stock, $profit, $amount)
    {
        $this->connection->insert('`stocks-api`.transactions', [
            'symbol' => $stock->getSymbol(),
            'amount' => $amount,
            'action' => 'buy',
            'profit' => $profit,
            'owner_id' => $_SESSION['user'],
        ]);
    }
}