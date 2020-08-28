<?php

interface AccountRepositoryInterface
{
    public function createAccount(Account $account): void;

    public function getAccount(int $accountId): Account;

    public function getAllAccounts(): array ;

    public function getAccountBalance(int $accountId): float;

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
            if ($account->getId() === $accountId) {
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
        return ($money > 0 ? '+' : '') . "$money$<br/>";
    }
}

class MySQLAccountRepository implements AccountRepositoryInterface
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function hydrate(array $data): Account
    {
        $account = new Account();
        $account->setId($data['id']);
        $account->setTitle($data['title']);
        $account->setAccountBalance($data['balance']);

        return $account;
    }

    public function createAccount(Account $account): void
    {
        $sql = "INSERT accounts (title, balance)
            VALUES('{$account->getTitle()}', '{$account->getBalance()}')";
        $this->pdo->exec($sql);
    }

    public function getAccount(int $accountId): Account
    {
        $stmt = $this->pdo->prepare("SELECT * FROM accounts WHERE id=?");
        $stmt->execute([$accountId]);
        $data = $stmt->fetch();

        if (!$data['id'] === $accountId) {
            throw new AccountNotFoundException();
        }

        return $this->hydrate($data);
    }

    public function getAllAccounts() : array
    {
        $data = $this->pdo->query("SELECT * FROM accounts")->fetchAll();

        $accounts = [];

        foreach ($data as $row) {
            $accounts[] = $this->hydrate($row);
        }

        return $accounts;
    }

    public function getAccountBalance(int $accountId): float
    {
        $stmt = $this->pdo->prepare("SELECT * FROM accounts WHERE id=?");
        $stmt->execute([$accountId]);
        $date = $stmt->fetch();

        return $date['balance'];
    }

    public function getBalanceOfAllAccounts(): float
    {
        $balanceOfAll = 0;

        $data = $this->pdo->query("SELECT * FROM accounts")->fetchAll();

        foreach ($data as $row) {
            $balanceOfAll += $row['balance'];
        }

        return $balanceOfAll;

    }

    public function update(Account $account, float $money): void
    {
        $updateAccountBalance = $account->getBalance() + $money;

        $sql = "UPDATE accounts SET balance=? WHERE id=?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$updateAccountBalance, $account->getId()]);
    }

    public function getPrettierMoney(float $money): string
    {
        return ($money > 0 ? '+' : '') . "$money$<br/>";
    }
}