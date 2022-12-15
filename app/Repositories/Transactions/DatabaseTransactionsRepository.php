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

    public function createSellTransaction(StockModel $stock, $profit)
    {
        $this->connection->insert('`stocks-api`.transactions', [
            'symbol' => $stock->getSymbol(),
            'amount' => $_POST['sell'],
            'action' => 'sell',
            'profit' => $profit,
            'owner_id' => $_SESSION['user'],
            ]);
    }

    public function createBuyTransaction(StockModel $stock, $profit)
    {
        $this->connection->insert('`stocks-api`.transactions', [
            'symbol' => $stock->getSymbol(),
            'amount' => $_POST['amount'],
            'action' => 'buy',
            'profit' => $profit,
            'owner_id' => $_SESSION['user'],
        ]);
    }
}