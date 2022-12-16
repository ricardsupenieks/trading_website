<?php

namespace App\Validation;

use App\Database;
use Doctrine\DBAL\Connection;

class UserValidation implements ValidationInterface
{
    private Connection $connection;
    private string $email;

    public function __construct($email)
    {
        $this->connection = Database::getConnection();
        $this->email = $email;
    }

    public function success(): bool
    {
        $resultSet = $this->connection->executeQuery(
            'SELECT id FROM `stocks-api`.users WHERE email=?', [
            $this->email
            ]);

        $id = $resultSet->fetchOne();
        if ($id === false) {
            return false;
        }
        return true;
    }
}