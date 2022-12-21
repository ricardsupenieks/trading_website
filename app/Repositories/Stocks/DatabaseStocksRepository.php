<?php

namespace App\Repositories\Stocks;

use App\Database;
use App\Models\Stock\StockModel;
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
            $userStock['id'],
            $userStock['symbol'],
            $userStock['name'],
            $userStock['amount'],
            $userStock['price'],
            $userStock['owner_id']
        );
    }

    public function getStockBySymbol($symbol, $userId)
    {
        $userStock = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('stocks')
            ->where('symbol = ?')
            ->andWhere('owner_id = ?')
            ->setParameter(0, $symbol)
            ->setParameter(1, $userId)
            ->fetchAssociative();

        if(!$userStock){
            return null;
        }
        return new UserStockModel(
            $userStock['id'],
            $userStock['symbol'],
            $userStock['name'],
            $userStock['amount'],
            $userStock['price'],
            $userStock['owner_id']
        );
    }

    public function saveStock(StockModel $stock, $ownerId, $amount): void
    {
        $userStockAmount = $this->connection->createQueryBuilder()
            ->select( 'amount')
            ->from('stocks')
            ->where('owner_id = ?')
            ->andWhere('name = ?')
            ->setParameter(0, $ownerId)
            ->setParameter(1, $stock->getName())
            ->fetchOne();


        if (!$userStockAmount) {
            $this->connection->insert('`stocks-api`.stocks', [
                'symbol' => $stock->getSymbol(),
                'name' => $stock->getName(),
                'amount' => $amount,
                'price' => $stock->getPrice(),
                'owner_id' => $ownerId,
            ]);
        } else {
            if($userStockAmount + $amount != 0) {
                $this->connection->update('`stocks-api`.stocks',
                    ['amount' => $userStockAmount + $amount],
                    ['owner_id' => $ownerId, 'symbol' => $stock->getSymbol()]
                );
            } else {
                $this->connection->delete('`stocks-api`.stocks', ['owner_id' => $ownerId, 'symbol' => $stock->getSymbol()]);
            }
        }
    }

    public function borrowStock(StockModel $stock, $ownerId, $amount): void
    {
        $userStockAmount = $this->connection->createQueryBuilder()
            ->select( 'amount')
            ->from('stocks')
            ->where('owner_id = ?')
            ->andWhere('name = ?')
            ->setParameter(0, $ownerId)
            ->setParameter(1, $stock->getName())
            ->fetchOne();


        if (!$userStockAmount) {
            $this->connection->insert('`stocks-api`.stocks', [
                'symbol' => $stock->getSymbol(),
                'name' => $stock->getName(),
                'amount' => $amount,
                'price' => $stock->getPrice(),
                'owner_id' => $ownerId,
            ]);
        } else {
            $this->connection->update('`stocks-api`.stocks',
                ['amount' => $userStockAmount + $amount],
                ['owner_id' => $ownerId, 'symbol' => $stock->getSymbol()]
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