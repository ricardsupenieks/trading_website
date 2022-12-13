<?php

namespace App\ViewVariables;

use App\Database;

class ViewProfitVariable implements ViewVariables
{

    public function getName()
    {
        return 'total';
    }

    public function getValue()
    {
        if (! isset($_SESSION['user'])) {
            return [];
        }

        $queryBuilder = Database::getConnection()->createQueryBuilder();

        $transactions = $queryBuilder
            ->select('*')
            ->from('transactions')
            ->where('owner_id = ?')
            ->setParameter(0, $_SESSION['user'])
            ->fetchAllAssociative();

        $totalProfit = 0;

        foreach($transactions as $transaction) {
            $totalProfit+=$transaction['profit'];
        }

        return ['profit' => $totalProfit];
    }
}