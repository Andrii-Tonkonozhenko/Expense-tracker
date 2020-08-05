<?php

interface AccountRepositoryInterface
{
    public function createAccount(Account $account);
}

class InMemoryAccountRepository implements AccountRepositoryInterface
{
    private $accounts = [];

    public function createAccount(Account $account) : void
    {
        $account->setId(count($this->accounts) + 1);
        $this->accounts[] = $account;
    }

    public function getAccount(int $id)
    {
        foreach ($this->accounts as $account) {
            if ($account->getId() === $id) {
                return $account;
            }
        }
    }

    public function update(int $id_account, float $money): void
    {
        foreach ($this->accounts as $account) {
            if ($account->getId() === $id_account) {
                $account->setAccountBalance($account->getBalance() + $money);
            }
        }
    }
}