<?php

class Record
{
    private $id;
    private $id_account;
    private $id_category;
    private $money;
    private $date;

    public function __construct(int $id_account, int $id_category, float $money, $date)
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
}
