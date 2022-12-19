<?php

namespace App\Controllers;

use App\Database;
use App\Models\Stock\StockModel;
use App\Redirect;
use App\Services\Stock\StockService;
use App\Services\User\UserService;
use App\Template;
use App\Validation\LoginValidation;
use App\Validation\UserStockAmountValidation;
use App\Validation\UserValidation;

class TransferController
{
    public function showForm(): Template
    {
        return new Template('transfer.twig');
    }

    public function execute(): Redirect
    {
        $stockService = new StockService();

        $stockValidation = $stockService->getUserStockBySymbol($_POST['symbol']);
        if ($stockValidation === null) {
            $_SESSION['errors']['incorrectStockSymbol'] = true;

            return new Redirect('/transfer');
        }

        $userValidation = new UserValidation($_POST['email']);
        if ($userValidation->success() === false) {
            $_SESSION['errors']['incorrectTransferEmail'] = true;

            return new Redirect('/transfer');
        }

        //velak sadalisu sito dalu ka pienakas
        $connection = Database::getConnection();

        $resultSet = $connection->executeQuery(
            'SELECT * FROM `stocks-api`.users WHERE id=?', [
            $_SESSION['user']]);

        $info = $resultSet->fetchAssociative();

        $passwordValidation = new LoginValidation($info['email'], $_POST['password']);
        if ($passwordValidation->checkPassword() == false) {
            $_SESSION['errors']['incorrectPassword'] = true;

            return new Redirect('/transfer');
        }

        $stockService = new StockService();
        $userStock = $stockService->getUserStockBySymbol($_POST['symbol']);

        $userStockAmountValidation = new UserStockAmountValidation($userStock->getAmount(), $_POST['amount']);
        if ($userStockAmountValidation->success() === true) {
            $_POST['amount'] = $userStock->getAmount();
        }

        $funds = $userStock->getAmount() - $_POST['amount'];
        $stockService->updateStock($funds, $userStock->getId());

        $userService = new UserService();
        $userId = $userService->getUserId($_POST['email']);

        $stock = $stockService->getStock($_POST['symbol']);
        $stock = new StockModel(
            $stock->getSymbol(),
            $stock->getName(),
            $stock->getPrice(),
            $stock->getHighPrice()
        );

        $stockService->saveStock($stock, $userId, $_POST['amount']);

        return new Redirect('/transfer');
    }
}