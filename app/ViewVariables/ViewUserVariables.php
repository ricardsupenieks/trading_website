<?php

namespace App\ViewVariables;

use App\Database;

class ViewUserVariables implements ViewVariables
{
    public function getName(): string {
        return 'user'; // saprotu to ka itka nevajadzetu nosaukt par user jo tad var but tikai viens user, bet sajai majaslapai der
    }

    public function getValue(): array {

        if (! isset($_SESSION['user'])) {
            return [];
        }

        $queryBuilder = Database::getConnection()->createQueryBuilder();

        $user = $queryBuilder
            ->select('*')
            ->from('users')
            ->where('id = ?')
            ->setParameter(0, $_SESSION['user'])
            ->fetchAssociative();

        $money = "0.00";
        if ($user['money'] !== null) {
            $money = $user['money'];
        }

        return [
            "id" => $user['id'],
            "name" => $user['name'],
            "email" => $user['email'],
            "money" => $money,
        ];
    }
}