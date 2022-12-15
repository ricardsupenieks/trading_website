<?php

namespace App\Repositories\Stocks;

use App\Database;
use App\Models\Stock\UserStockModel;
use Doctrine\DBAL\Connection;

class DatabaseStocksRepository implements StocksRepository
{
    private Connection $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function getStock(): UserStockModel
    {
        $userStock = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('stocks')
            ->where('id = ?')
            ->setParameter(0, $_SESSION['stockId'])
            ->fetchAssociative();

        return new UserStockModel(
            $userStock['symbol'],
            $userStock['name'],
            $userStock['amount'],
            $userStock['price'],
            $userStock['owner_id']);
    }

    public function saveStock(UserStockModel $stock): void
    {
        $this->connection->insert('`stocks-api`.stocks', [
            'symbol' => $stock->getSymbol(),
            'name' => $stock->getName(),
            'amount' => $stock->getAmount(),
            'price' => $stock->getPrice(),
            'owner_id' => $stock->getOwnerId(),
        ]);
    }

    public function updateStock($totalAmount): void
    {
        if ($totalAmount == 0) {
            $this->connection->delete('`stocks-api`.stocks', ['id' => $_SESSION['stockId']]);
        }

        $this->connection->update('`stocks-api`.stocks',
            ['amount' => $totalAmount],
            ['id' => $_SESSION['stockId']]
        );
    }
}