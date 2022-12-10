<?php

namespace App\Models\Collections;

use App\Models\Stock\StockModel;

class StockCollection
{
    private array $stocks = [];

    public function __construct(array $stocks)
    {
        foreach ($stocks as $stock) {
            $this->add($stock);
        }
    }

    public function add(StockModel $stock): void
    {
        $this->stocks[] = $stock;
    }

    public function getStocks(): array
    {
        return $this->stocks;
    }
}