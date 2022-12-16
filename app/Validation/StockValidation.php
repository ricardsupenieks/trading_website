<?php

namespace App\Validation;

use Finnhub\Api\DefaultApi;
use Finnhub\Configuration;
use GuzzleHttp\Client;

class StockValidation implements ValidationInterface
{
    private DefaultApi $client;
    private string $stockSymbol;

    public function __construct($stockSymbol)
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('token', $_ENV["API_KEY"]);
        $this->client = new DefaultApi(
            new Client(),
            $config
        );
        $this->stockSymbol = $stockSymbol;
    }

    public function success(): bool
    {
        $stockName = json_decode($this->client->symbolSearch($this->stockSymbol)->toHeaderValue(), true)["result"][0]["description"];
        $stockPrice = json_decode($this->client->quote($this->stockSymbol)->toHeaderValue(), true)["c"];
        $stockPriceChange = json_decode($this->client->quote($this->stockSymbol)->toHeaderValue(), true)["dp"];

        if ($this->stockSymbol == null || $stockName == null) {
            if ($stockPriceChange == null && $stockPrice == null) {
                return false;
            }
        }
        return true;
    }
}