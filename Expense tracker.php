<?php

require 'Bank account.php';
require 'Tracker Exception.php';

class ExpenseTracker
{
    private $accounts = [];

    public function addAccount(BankAccount $account) : void
    {
        $this->accounts[$account->getId()] = $account;
    }

    public function getAccountBalance(int $account) : float
    {
        if (!$this->accounts[$account]) {
            throw new AccountNotFound();
        }

        return $this->accounts[$account]->getAccountBalance();
    }

    public function accountReplenishment(int $account, float $money) : void
    {
        if (!$this->accounts[$account]) {
            throw new AccountNotFound();
        }

        $this->accounts[$account]->accountReplenishment($money);
    }

    public function setCosts(int $account, float $money) : void
    {
        if (!$this->accounts[$account]) {
            throw new AccountNotFound();
        }

        $this->accounts[$account]->setCosts($money);
    }

    public function showCostHistory(int $account): void
    {
        if (!$this->accounts[$account]) {
            throw new AccountNotFound();
        }

        $this->accounts[$account]->costHistory();
    }

    public function showAccountReplenishmentHistory(int $account): void
    {
        if (!$this->accounts[$account]) {
            throw new AccountNotFound();
        }

        $this->accounts[$account]->accountReplenishmentHistory();
    }
}

$expense_tracker = new ExpenseTracker();

$monoBankStudy = new BankAccount(1,'MonoBank','Study');
$monoBankFamily = new BankAccount(2,'MonoBank','Family');
$privateBankAndrii = new BankAccount(3,'PrivateBank','Andrii');
try {
    $expense_tracker->addAccount($monoBankFamily);
    $expense_tracker->addAccount($monoBankStudy);
    $expense_tracker->addAccount($privateBankAndrii);

    $expense_tracker->accountReplenishment(1, 200);
    $expense_tracker->accountReplenishment(1, 15);
    $expense_tracker->accountReplenishment(1, 22.4);
    $expense_tracker->setCosts(1, 55);
    $expense_tracker->setCosts(1, 15);
//    $expense_tracker->showCostHistory(1);
    $expense_tracker->showAccountReplenishmentHistory(1);
} catch (ExpenseTrackerException $e) {
    die($e->getMessage());
}
//echo $expense_tracker->getAccountBalance(1);

