<?php

namespace App\ViewVariables;

use App\Database;

class ViewTransactionVariables implements ViewVariables
{

    public function getName()
    {
        return "transactions";
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

        foreach($transactions as $transaction) {
            $totalProfit+=$transaction['profit'];
        }

        return $transactions;
    }
}