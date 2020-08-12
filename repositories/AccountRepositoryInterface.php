<?php

interface AccountRepositoryInterface
{
    public function createAccount(Account $account);

    public function getAccount(int $id_account): Account;

    public function getAllAccounts(): array;

    public function getAccountBalance(int $account): void;

    public function getBalanceOfAllAccounts(): void;

    public function update(Account $account, float $money): void;

    public function getPrettierMoney(float $money): string;
}

class InMemoryAccountRepository implements AccountRepositoryInterface
{
    private $accounts = [];

    public function createAccount(Account $account): void
    {
        $account->setId(count($this->accounts) + 1);
        $this->accounts[] = $account;
    }

    /**
     * @param int $id
     * @return Account|null
     * @throws AccountNotFoundException
     */
    public function getAccount(int $id): Account
    {
        $account = null;

        foreach ($this->accounts as $accounts) {
            if ($accounts->getId() === $id) {
               $account = $accounts;
            }
        }
            if ($account === null) {
                throw new AccountNotFoundException();
            }

        return $account;
    }

    public function getAllAccounts(): array
    {
        return $this->accounts;
    }

    public function getAccountBalance(int $accountId): void
    {
        foreach ($this->accounts as $account) {
            if ($account->getId() === $accountId)
            {
                echo 'Account: ' . $account->getTitle() . ' account balance: ' . $account->getBalance() . "</br>";
            }
        }
    }

    public function getBalanceOfAllAccounts(): void
    {
        $balanceOfAll = 0;

        foreach ($this->accounts as $account) {
            $balanceOfAll += $account->getBalance();
        }

        echo $balanceOfAll . '</br>';
    }

    public function update(Account $account, float $money): void
    {
        $account->setAccountBalance($account->getBalance() + $money);
    }

    public function getPrettierMoney(float $money): string
    {
        if ($money > 0) {
            return ' + ' . $money . '$' . "</br>";
        }else{
            return ' ' . $money . '$' . "</br>";
        }
    }
}