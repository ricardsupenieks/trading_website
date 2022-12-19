<?php

namespace App\Repositories\User;

use App\Database;
use App\Models\User\UserModel;
use Doctrine\DBAL\Connection;

class DatabaseUserRepository implements UserRepository
{
    private Connection $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();

    }

    public function storeUser(UserModel $user): void
    {
        $this->connection->insert('users', [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => password_hash($user->getPassword(), PASSWORD_DEFAULT)
        ]);
    }

    public function getUser(string $email): string
    {
        $resultSet = $this->connection->executeQuery(
            'SELECT id FROM `stocks-api`.users WHERE email=?', [
            $email]);

        return $resultSet->fetchOne();
    }
}