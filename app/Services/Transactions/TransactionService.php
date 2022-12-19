<?php

namespace App\Services\Transactions;

use App\Repositories\Transactions\DatabaseTransactionsRepository;

class TransactionService
{
    private DatabaseTransactionsRepository $databaseTransactionsRepository;

    public function __construct()
    {
        $this->databaseTransactionsRepository = new DatabaseTransactionsRepository();
    }

    public function buyTransaction($stock, $profit, $amount)
    {
        $this->databaseTransactionsRepository->createBuyTransaction($stock, $profit, $amount);
    }

    public function sellTransaction($stock, $profit, $amount)
    {
        $this->databaseTransactionsRepository->createSellTransaction($stock, $profit, $amount);
    }
}