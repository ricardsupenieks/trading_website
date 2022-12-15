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

    public function buyTransaction($stock, $profit)
    {
        $this->databaseTransactionsRepository->createBuyTransaction($stock, $profit);
    }

    public function sellTransaction($stock, $profit)
    {
        $this->databaseTransactionsRepository->createSellTransaction($stock, $profit);
    }
}