<?php

namespace App\Repositories\User;

use App\Database;
use App\Models\User\UserModel;
use Doctrine\DBAL\Connection;

class DatabaseUserRepository implements UserRepository
{
    private Connection $connection;
    private UserModel $user;

    public function __construct(UserModel $user)
    {
        $this->connection = Database::getConnection();

        $this->user = $user;
    }

    public function storeUser(): void
    {
        $this->connection->insert('users', [
            'name' => $this->user->getName(),
            'email' => $this->user->getEmail(),
            'password' => password_hash($this->user->getPassword(), PASSWORD_DEFAULT)
        ]);

    }

    public function getUser(): string
    {
        $resultSet = $this->connection->executeQuery(
            'SELECT id FROM `stocks-api`.users WHERE email=?', [
            $this->user->getEmail()]);

        $id = $resultSet->fetchOne();

        return $id;
    }
}