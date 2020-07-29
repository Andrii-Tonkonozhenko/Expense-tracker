<?php

class BankAccount
{
    private $id;
    private $bankTitle;
    private $accountTitle;
    private $accountBalance = 0;
    private $costHistory = [];
    private $accountReplenishmentHistory = [];

    public function __construct(int $id, string $bankTitle, string $accountTitle)
    {
        $this->id = $id;
        $this->bankTitle = $bankTitle;
        $this->accountTitle = $accountTitle;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getBankTitle(): string
    {
        return $this->bankTitle;
    }

    public function getAccountTitle(): string
    {
        return $this->accountTitle;
    }

    public function getAccountBalance(): float
    {
        return $this->accountBalance;
    }

    public function accountReplenishment(float $money): void
    {
        $this->accountBalance += $money;
        $this->accountReplenishmentHistory[] =
            [
                'date' => (date("dS of F  h:I:s A ")),
                'bank' => $this->getBankTitle(),
                'account' => $this->getAccountTitle(),
                'money' => $money
            ];
    }

    public function setCosts(float $money): void
    {
        if ($this->accountBalance - $money >= 0) {
            $this->accountBalance = $this->accountBalance - $money;
            $this->costHistory[] =
                [
                    'data' => (date("dS of F  h:I:s A ")),
                    'bank' => $this->getBankTitle(),
                    'account' => $this->getAccountTitle(),
                    'costs' => $money
                ];
        } else {
            echo 'Insufficient funds in the account' . '</br>';
        }
    }

    public function costHistory(): void
    {
        foreach ($this->costHistory as $cost) {
            echo $cost['bank'] . ' Account: ' . $cost['account'] . ' ' . $cost['data'] . ' was spent ' . $cost['costs'] . '$' . '</br>';
        }
    }

    public function accountReplenishmentHistory(): void
    {
        foreach ($this->accountReplenishmentHistory as $accountReplenishment) {
            echo $accountReplenishment['date'] . ' ' . $accountReplenishment['bank'] . ' Account: ' . $accountReplenishment['account'] . ' was replenished ' . $accountReplenishment['money'] . '$' . '</br>';
        }
    }
}
