<?php

namespace App\Controllers;

use App\Database;
use App\Redirect;
use Doctrine\DBAL\Connection;

class FundsController
{
    private Connection $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();

        $this->funds = $this->connection->createQueryBuilder()
            ->select('money')
            ->from('users')
            ->where('id = ?')
            ->setParameter(0, $_SESSION['user'])
            ->fetchOne();

    }

    public function depositWithdraw(): Redirect
    {
        $totalFunds = floatval($_POST['deposit']) + floatval($this->funds) - floatval($_POST['withdraw']);
        $this->connection->update('`stocks-api`.users', ['money' => $totalFunds], ['id' => $_SESSION['user']]);

        return new Redirect('/profile');
    }
}