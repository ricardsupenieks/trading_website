<?php

class TransactionModel
{
    private $number;

    public function __construct($number)
    {
        $this->number = $number;
    }


    public function getNumber()
    {
        return $this->number;
    }
}