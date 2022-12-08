<?php

namespace App\Validation;

use App\Database;
use Doctrine\DBAL\Connection;

class LoginValidation implements ValidationInterface
{
    private Connection $connection;
    private string $email;
    private string $password;

    public function __construct(string $email, string $password)
    {
        $this->connection = Database::getConnection();

        $this->email = $email;
        $this->password = $password;
    }

    public function checkEmail(): bool
    {
        $emailsInDatabase = $this->connection->fetchAllKeyValue('SELECT id, email FROM `stocks-api`.users');
        if (in_array($this->email, $emailsInDatabase)) {
            return true;
        }
        return false;
    }

    public function checkPassword(): bool
    {
        $resultSet = $this->connection->executeQuery(
            'SELECT password FROM `stocks-api`.users WHERE email=?', [
            $this->email]);
        $hash = $resultSet->fetchAllAssociative();

        if (password_verify($this->password, $hash[0]["password"])) {
            return true;
        }
        return false;
    }

    public function success(): bool
    {
        if (!$this->checkEmail() || !$this->checkPassword()) {
            $_SESSION['errors']['userCredentials'] = false;
            return false;
        }
        return true;
    }
}