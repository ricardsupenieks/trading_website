<?php

namespace App\Validation;

use App\Database;
use Doctrine\DBAL\Connection;

class RegisterValidation implements ValidationInterface
{
    private Connection $connection;

    private string $email;
    private string $password;
    private string $passwordRepeat;

    public function __construct(string $email, string $password, string $passwordRepeat)
    {
        $this->connection = Database::getConnection();

        $this->email = $email;
        $this->password = $password;
        $this->passwordRepeat = $passwordRepeat;
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
        if ($this->password != $this->passwordRepeat) {
            return false;
        }
        return true;
    }

    public function success(): bool
    {
        if ($this->checkEmail()) {
            $_SESSION['errors']['emailTaken'] = true;
        }
        if ($this->checkPassword() === false) {
            $_SESSION['errors']['passwordsMatch'] = false;
        }

        if (empty($_SESSION['errors'])) {
            return true;
        }
        return false;
    }
}