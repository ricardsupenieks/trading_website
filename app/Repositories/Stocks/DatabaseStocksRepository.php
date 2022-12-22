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
            $userStock['total_price'],
            $userStock['avg_price'],
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
            $userStock['total_price'],
            $userStock['avg_price'],
            $userStock['owner_id']
        );
    }

    public function saveStock(StockModel $stock, $ownerId, $amount): void
    {
        $userStock = $this->connection->createQueryBuilder()
            ->select( '*')
            ->from('stocks')
            ->where('owner_id = ?')
            ->andWhere('name = ?')
            ->setParameter(0, $ownerId)
            ->setParameter(1, $stock->getName())
            ->fetchAssociative();

        if (!$userStock) {
            $this->connection->insert('`stocks-api`.stocks', [
                'symbol' => $stock->getSymbol(),
                'name' => $stock->getName(),
                'amount' => $amount,
                'total_price' => $stock->getPrice() * $amount,
                'avg_price' => $stock->getPrice(),
                'owner_id' => $ownerId,
            ]);
        } else {
            if($userStock['amount']+ $amount != 0) {
                $this->connection->update('`stocks-api`.stocks', [
                    'amount' => $userStock['amount'] + $amount,
                    'total_price' => $userStock['total_price'] + $stock->getPrice() * $amount,
                    'avg_price' => ($userStock['total_price'] + $stock->getPrice() * $amount) / ($userStock['amount'] + $amount)
                ],
                    ['owner_id' => $ownerId, 'symbol' => $stock->getSymbol()]
                );
            } else {
                $this->connection->delete('`stocks-api`.stocks', ['owner_id' => $ownerId, 'symbol' => $stock->getSymbol()]);
            }
        }
    }

    public function borrowStock($stock, $ownerId, $amount): void
    {
        $userStock = $this->connection->createQueryBuilder()
            ->select( '*')
            ->from('stocks')
            ->where('owner_id = ?')
            ->andWhere('name = ?')
            ->setParameter(0, $ownerId)
            ->setParameter(1, $stock->getName())
            ->fetchAssociative();

        if (!$userStock) {
            $this->connection->insert('`stocks-api`.stocks', [
                'symbol' => $stock->getSymbol(),
                'name' => $stock->getName(),
                'amount' => $amount,
                'total_price' => $stock->getPrice() * abs($amount),
                'avg_price' => $stock->getPrice(),
                'owner_id' => $ownerId,
            ]);
        } else {
            $this->connection->update('`stocks-api`.stocks', [
                'amount' => $userStock['amount'] - abs($amount),
                'total_price' => $userStock['total_price'] - $stock->getPrice() * abs($amount),
                'avg_price' => ($userStock['total_price'] - $stock->getPrice() * abs($amount)) / (abs($userStock['amount'] - abs($amount)))
                ],
                ['owner_id' => $ownerId, 'symbol' => $stock->getSymbol()]
            );
        }
    }

    public function updateStock($totalAmount, $stockId, $buyAmount, $sellAmount, StockModel $stock): void
    {
        $userStock = $this->connection->createQueryBuilder()
            ->select( '*')
            ->from('stocks')
            ->where('id = ?')
            ->setParameter(0, $stockId)
            ->fetchAssociative();

        if ($totalAmount == 0) {
            $this->connection->delete('`stocks-api`.stocks', ['id' => $stockId]);
        }

        if($userStock['amount'] < 0) {
            $this->connection->update('`stocks-api`.stocks', [
                'amount' => $totalAmount,
                'total_price' => $userStock['total_price'] - $stock->getPrice() * abs($buyAmount) + $stock->getPrice() * abs($sellAmount),
                'avg_price' =>
                    ($userStock['total_price'] - $stock->getPrice() * abs($buyAmount) +
                        $stock->getPrice() * abs($sellAmount)) / (abs($totalAmount))
            ],
                ['id' => $stockId]
            );
        } else {
            $this->connection->update('`stocks-api`.stocks', [
                'amount' => $totalAmount,
                'total_price' => $userStock['total_price'] + $stock->getPrice() * abs($buyAmount) - $stock->getPrice() * abs($sellAmount),
                'avg_price' =>
                    ($userStock['total_price'] + $stock->getPrice() * abs($buyAmount) -
                        $stock->getPrice() * abs($sellAmount)) / (abs($totalAmount))
            ],
                ['id' => $stockId]
            );
        }
    }
}