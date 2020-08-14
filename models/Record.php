<?php

class Record
{
    private $id;
    private $id_account;
    private $id_category;
    private $money;
    private $date;

    public function __construct(int $id_account, int $id_category, float $money, DateTime $date)
    {
        $this->id_account = $id_account;
        $this->id_category = $id_category;
        $this->money = $money;
        $this->date = $date;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getAccountId(): int
    {
        return $this->id_account;
    }

    public function getCategoryId(): int
    {
        return $this->id_category;
    }

    public function getMoney(): float
    {
        return $this->money;
    }

    public function getDate() : DateTime
    {
        return $this->date;
    }
}
