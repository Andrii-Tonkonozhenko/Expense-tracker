<?php

interface AccountRepositoryInterface
{
    public function createAccount(Account $account);

    public function getAccount(int $accountId): Account;

    public function getAllAccounts(): array;

    public function getAccountBalance(int $account): float;

    public function getBalanceOfAllAccounts(): float;

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
     * @param int $accountId
     * @return Account|null
     * @throws AccountNotFoundException
     */
    public function getAccount(int $accountId): Account
    {
        $account = null;

        foreach ($this->accounts as $accounts) {
            if ($accounts->getId() === $accountId) {
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

    public function getAccountBalance(int $accountId): float
    {
        foreach ($this->accounts as $account) {
            if ($account->getId() === $accountId)
            {
               return $account->getBalance();
            }
        }
    }

    public function getBalanceOfAllAccounts(): float
    {
        $balanceOfAll = 0;

        foreach ($this->accounts as $account) {
            $balanceOfAll += $account->getBalance();
        }

       return $balanceOfAll;
    }

    public function update(Account $account, float $money): void
    {
        $account->setAccountBalance($account->getBalance() + $money);
    }

    public function getPrettierMoney(float $money): string
    {
        return ($money > 0 ? '+' : ''). "$money$<br/>";
    }
}