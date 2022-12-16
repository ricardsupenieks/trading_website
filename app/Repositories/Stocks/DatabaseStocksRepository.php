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

    public function getStock($variableToGetInformation): UserStockModel
    {
        $stockId = $variableToGetInformation;

        $userStock = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('stocks')
            ->where('id = ?')
            ->setParameter(0, $stockId)
            ->fetchAssociative();

        return new UserStockModel(
            $userStock['symbol'],
            $userStock['name'],
            $userStock['amount'],
            $userStock['price'],
            $userStock['owner_id']
        );
    }

    public function saveStock(UserStockModel $stock): void
    {
        $userStock = $this->connection->createQueryBuilder()
            ->select('name', 'amount')
            ->from('stocks')
            ->where('symbol = ?')
            ->setParameter(0, $stock->getSymbol())
            ->fetchAssociative();

        if (empty($userStock)) {
            $this->connection->insert('`stocks-api`.stocks', [
                'symbol' => $stock->getSymbol(),
                'name' => $stock->getName(),
                'amount' => $stock->getAmount(),
                'price' => $stock->getPrice(),
                'owner_id' => $stock->getOwnerId(),
            ]);
        } else {
            $this->connection->update('`stocks-api`.stocks',
                ['amount' =>  $userStock['amount'] + $stock->getAmount()],
                ['symbol' => $stock->getSymbol()]
            );
        }
    }

    public function updateStock($totalAmount, $stockId): void
    {
        if ($totalAmount == 0) {
            $this->connection->delete('`stocks-api`.stocks', ['id' => $stockId]);
        }

        $this->connection->update('`stocks-api`.stocks',
            ['amount' => $totalAmount],
            ['id' => $stockId]
        );
    }
}