<?php

namespace App\Controllers;

use App\Database;
use App\Redirect;
use App\Services\Stock\StockService;
use App\Template;
use Doctrine\DBAL\Connection;

class InspectStockController
{
    private Connection $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function index()
    {
        $stockService = new StockService();

        if(!empty($_GET['stock'])) {
            $_SESSION['stockId'] = $_GET['stock'];
        }

        $userStock = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('stocks')
            ->where('id = ?')
            ->setParameter(0, $_SESSION['stockId'])
            ->fetchAssociative();

        $stock = $stockService->getStock($userStock['symbol']);

        return new Template('inspect.twig', ['stock' => $stock, 'userStock' => $userStock]);
    }

    public function execute()
    {

        $stockService = new StockService();

        $funds = $this->connection->createQueryBuilder()
            ->select('money')
            ->from('users')
            ->where('id = ?')
            ->setParameter(0, $_SESSION['user'])
            ->fetchOne();

        $userStock = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('stocks')
            ->where('id = ?')
            ->setParameter(0, $_SESSION['stockId'])
            ->fetchAssociative();


        if ((int)$userStock['amount'] - (int)$_POST['sell'] < 0) {
            $_POST['sell'] = $userStock['amount'];
        }

        $stock = $stockService->getStock($userStock['symbol']);

        $totalFunds = (float)$funds + ($stock->getPrice() * (int)$_POST['sell']) - ($stock->getPrice() * (int)$_POST['buy']);


        if($totalFunds < 0) {
            $_SESSION['errors']['insufficientFunds'] = true;

            return new Redirect('/inspect');
        }

        $totalAmount = $userStock['amount'] + $_POST['buy'] - $_POST['sell'];

        $this->connection->update('`stocks-api`.users', ['money' => $totalFunds], ['id' => $_SESSION['user']]);
        $this->connection->update('`stocks-api`.stocks', ['amount' => $totalAmount], ['id' => $_SESSION['stockId']]);


        if ($_POST['sell'] !== "") {
            $profit = $stock->getPrice() * $_POST['sell'] - $userStock['price'] * $_POST['sell'];

            $this->connection->insert('`stocks-api`.transactions', [
                'symbol' => $stock->getSymbol(),
                'amount' => $_POST['sell'],
                'action' => 'sell',
                'profit' => $profit,
                'owner_id' => $_SESSION['user']
            ]);
        }

        if ($_POST['buy'] !== "") {
            $this->connection->insert('`stocks-api`.transactions', [
                'symbol' => $stock->getSymbol(),
                'amount' => $_POST['buy'],
                'action' => 'buy',
                'owner_id' => $_SESSION['user']
            ]);
        }

        return new Redirect('/');
    }
}