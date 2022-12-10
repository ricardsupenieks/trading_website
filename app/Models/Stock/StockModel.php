<?php

namespace App\Models\Stock;

class StockModel
{
    private string $symbol;
    private string $name;
    private float $price;
    private float $priceChange;

    public function __construct(string $symbol, string $name, float $price, float $priceChange)
    {
        $this->symbol = $symbol;
        $this->name = $name;
        $this->price = $price;
        $this->priceChange = $priceChange;
    }


    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getPriceChange(): float
    {
        return $this->priceChange;
    }
}