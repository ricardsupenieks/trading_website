<?php

namespace App\Models\Stock;

class UserStockModel
{
    private string $symbol;
    private string $name;
    private int $amount;
    private ?int $ownerId;
    private int $id;
    private float$totalPrice;
    private float $averagePrice;

    public function __construct($id, $symbol, $name, $amount, $totalPrice, $averagePrice ,$ownerId = null)
    {
        $this->symbol = $symbol;
        $this->name = $name;
        $this->amount = $amount;
        $this->ownerId = $ownerId;
        $this->id = $id;
        $this->totalPrice = $totalPrice;
        $this->averagePrice = $averagePrice;
    }


    public function getId()
    {
        return $this->id;
    }

    public function getSymbol()
    {
        return $this->symbol;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getOwnerId()
    {
        return $this->ownerId;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function getAveragePrice(): float
    {
        return $this->averagePrice;
    }
}