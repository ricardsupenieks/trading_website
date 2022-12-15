<?php

namespace App\Repositories\Funds;

use App\Database;
use Doctrine\DBAL\Connection;

class DatabaseFundsRepository implements FundsRepository
{
    private string $funds;
    private Connection $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();

        $queryBuilder = $this->connection->createQueryBuilder();

        $this->funds = $queryBuilder
            ->select('money')
            ->from('users')
            ->where('id = ?')
            ->setParameter(0, $_SESSION['user'])
            ->fetchOne();
    }

    public function getFunds():float
    {
        return (float)$this->funds;
    }

    public function updateFunds($amount): void
    {
        $this->connection->update('`stocks-api`.users', ['money' => $amount], ['id' => $_SESSION['user']]);
    }
}