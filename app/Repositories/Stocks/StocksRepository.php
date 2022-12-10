<?php

use App\Models\Stock\StockModel;

interface StocksRepository
{
    public function getStock(): StockModel;
}