<?php

class Account
{
    private $title;
    private $id;
    private $accountBalance;

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setAccountBalance(float $accountBalance): void
    {
        $this->accountBalance = $accountBalance;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getBalance() : float
    {
       return $this->accountBalance;
    }

}