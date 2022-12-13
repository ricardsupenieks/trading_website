<?php

namespace App\Models\Stock;

class StockModel
{
    private string $symbol;
    private string $name;
    private ?float $price;
    private ?float $priceChange;
    private ?float $highPrice;

    public function __construct(string $symbol, string $name, ?float $price = null, ?float $highPrice = null, ?float $priceChange = null)
    {
        $this->symbol = $symbol;
        $this->name = $name;
        $this->price = $price;
        $this->priceChange = $priceChange;
        $this->highPrice = $highPrice;
    }


    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getHighPrice(): ?float
    {
        return $this->highPrice;
    }

    public function getPriceChange(): ?float
    {
        return $this->priceChange;
    }
}