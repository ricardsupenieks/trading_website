<?php

namespace App\ViewVariables;

use App\Database;

class ViewStockVariables implements ViewVariables
{
    public function getName(): string
    {
        return 'boughtStocks';
    }

    public function getValue()
    {
        if (! isset($_SESSION['user'])) {
            return [];
        }

        $this->connection = Database::getConnection();

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