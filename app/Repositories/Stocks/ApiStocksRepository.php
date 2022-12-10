<?php

namespace App\Repositories\Stocks;

use App\Models\Stock\StockModel;
use Finnhub\Api\DefaultApi;
use Finnhub\Configuration;
use GuzzleHttp\Client;

class ApiStocksRepository
{

    private DefaultApi $client;

    public function __construct()
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('token', $_ENV["API_KEY"]);
        $this->client = new DefaultApi(
            new Client(),
            $config
        );
    }

    public function getStock(string $stockSymbol): StockModel
    {
            $stockName = json_decode($this->client->symbolSearch($stockSymbol)->toHeaderValue(), true)["result"][0]["description"];
            $stockPrice = json_decode($this->client->quote($stockSymbol)->toHeaderValue(), true)["c"];
            $stockPriceChange = json_decode($this->client->quote($stockSymbol)->toHeaderValue(), true)["d"];

            return new StockModel($stockSymbol, $stockName, $stockPrice, $stockPriceChange);
    }
}