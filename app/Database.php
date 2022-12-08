<?php

namespace App;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class Database
{
    private static ?Connection $connection = null;

    public static function getConnection(): Connection
    {
        if (self::$connection == null) {
            $connectionParams = [
                'dbname' => 'stocks-api',
                'user' => 'root',
                'password' => $_ENV['MYSQL_PASSWORD'],
                'host' => $_ENV['MYSQL_HOST'],
                'driver' => 'pdo_mysql',
            ];

            self::$connection = DriverManager::getConnection($connectionParams);
        }

        return self::$connection;
    }
}