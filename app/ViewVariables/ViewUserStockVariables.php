<?php

namespace App\ViewVariables;

use App\Database;

class ViewUserStockVariables implements ViewVariables
{

    public function getName()
    {
        return 'userStock';
    }

    public function getValue()
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();

        return $queryBuilder
            ->select('*')
            ->from('stocks')
            ->where('id = ?')
            ->setParameter(0, $_SESSION['stockId'])
            ->fetchAssociative();
    }
}