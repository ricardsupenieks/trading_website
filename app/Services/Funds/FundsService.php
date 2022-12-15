<?php

namespace App\Services\Funds;

use App\Repositories\Funds\DatabaseFundsRepository;

class FundsService
{
    private DatabaseFundsRepository $fundsRepository;

    public function __construct()
    {
        $this->fundsRepository = new DatabaseFundsRepository();
    }

    public function getFunds()
    {
        return $this->fundsRepository->getFunds();
    }

    public function updateFunds($funds)
    {
        $this->fundsRepository->updateFunds($funds);
    }
}