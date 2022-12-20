<?php

namespace App\Models\Stock;

class UserStockModel
{
    private string $symbol;
    private string $name;
    private int $amount;
    private float $price;
    private int $ownerId;
    private int $id;

    public function __construct($id, $symbol, $name, $amount, $price, $ownerId = null)
    {
        $this->symbol = $symbol;
        $this->name = $name;
        $this->amount = $amount;
        $this->price = $price;
        $this->ownerId = $ownerId;
        $this->id = $id;
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

    public function getPrice()
    {
        return $this->price;
    }

    public function getOwnerId()
    {
        return $this->ownerId;
    }
}