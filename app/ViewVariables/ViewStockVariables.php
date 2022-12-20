<?php

namespace App\ViewVariables;

use App\Database;
use Doctrine\DBAL\Connection;

class ViewStockVariables implements ViewVariables
{
    private Connection $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function getName(): string
    {
        return 'boughtStocks';
    }

    public function getValue()
    {
        if (! isset($_SESSION['user'])) {
            return [];
        }

        $queryBuilder = $this->connection->createQueryBuilder();

        $stocks = $queryBuilder
            ->select('*')
            ->from('stocks')
            ->where('owner_id = ?')
            ->setParameter(0, $_SESSION['user'])
            ->fetchAllAssociative();

        return $stocks;
    }
}