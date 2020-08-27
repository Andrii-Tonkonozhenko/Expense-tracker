<?php

interface RecordRepositoryInterface
{
    public function createRecord(Record $record): void;

    public function getAllRecords(): array;

    public function getCategoryBalance(int $accountId, int $categoryId): float;

    public function findAccountById(int $accountId): array;

    public function findByCategoryId(int $categoryId): array;

    public function findByAccountIdAndCategoryId(int $accountId, int $categoryId): array;

    public function findAllSortedByDateDesc(): array;

    public function findAccountRecordsByDate(int $accountId, string $date): array;

    public function findRecordsByDate(string $date): array;

    public function findAccountRecordsByWeek(int $accountId): array;

    public function findRecordsBetweenDates(int $accountId, string $fistDate, string $secondDate): array;

}

class InMemoryRecordRepository implements RecordRepositoryInterface
{
    private $records = [];

    public function createRecord(Record $record): void
    {
        $record->setId(count($this->records) + 1);
        $this->records[] = $record;
    }

    /**
     * @return Record[]
     */
    public function getAllRecords(): array
    {
        return $this->records;
    }

    public function findAccountById(int $accountId): array
    {
        $record = [];
        foreach ($this->records as $record) {
            if ($record->getAccountId() === $accountId) {
                $record[] = $record;
            }
        }

        return $record;
    }

    public function findByCategoryId(int $categoryId): array
    {
        $records = [];
        foreach ($this->records as $record) {
            if ($record->getCategoryId() === $categoryId) {
                $records[] = $record;
            }
        }

        return $records;
    }

    public function findByAccountIdAndCategoryId(int $accountId, int $categoryId): array
    {
        $records = [];

        foreach ($this->records as $record) {
            if ($record->getAccountId() === $accountId && $record->getCategoryId() === $categoryId) {
                $records[] = $record;
            }
        }

        return $records;
    }

    public function findRecordsByDate($date): array
    {
        $records = [];

        foreach ($this->records as $record) {
            if (date('y m', strtotime($date)) === $record->getDate()->format('y m')) {
                $records[] = $record;
            }
        }

        return $records;
    }

    public function findAccountRecordsByDate(int $accountId, $date): array
    {
        $records = [];

        foreach ($this->records as $record) {
            if ($record->getAccountId() === $accountId && date('y m',
                    strtotime($date)) === $record->getDate()->format('y m')) {
                $records[] = $record;
            }
        }

        return $records;
    }

    public function findRecordsBetweenDates(int $accountId, string $fistDate, string $secondDate): array
    {
        $records = [];

        foreach ($this->records as $record) {
            if ($record->getAccountId() === $accountId && date('y m',
                    strtotime($fistDate)) <= $record->getDate()->format('y m') && date('y m',
                    strtotime($secondDate)) >= $record->getDate()->format('y m')) {
                $records[] = $record;
            }
        }

        return $records;
    }

    public function findAccountRecordsByWeek(int $accountId): array
    {
        $records = [];

        foreach ($this->records as $record) {
            if ($record->getAccountId() === $accountId && new DateTime('-7 days') < $record->getDate()) {
                $records[] = $record;
            }
        }

        return $records;
    }

    public function getCategoryBalance(int $accountId, int $categoryId): float
    {
        $sum = 0;

        foreach ($this->findByAccountIdAndCategoryId($accountId, $categoryId) as $record) {
            $sum += $record->getMoney();
        }

        return $sum;
    }

    public function findAllSortedByDateDesc(): array
    {
        $records = $this->records;

        usort($records, function (Record $a, Record $b) {
            return $b->getDate() <=> $a->getDate();
        });

        return $records;
    }
}

class MySQLRecordRepository implements RecordRepositoryInterface
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function hydrate(array $data): Record
    {
        $record = new Record($data['id_account'], $data['id_category'], $data['money'], new DateTime($data['date']));
        $record->setId($data['id']);

        return $record;
    }

    public function createRecord(Record $record): void
    {
        $sql = "INSERT records (id_account, id_category, money, date)
            VALUES('{$record->getAccountId()}','{$record->getCategoryId()}','{$record->getMoney()}','{$record->getDate()->format('Y-m-d H:i:s')}')";
        $this->pdo->exec($sql);
    }

    public function getAllRecords(): array
    {
        $data = $this->pdo->query("SELECT * FROM records")->fetchAll();

        $records = [];

        foreach ($data as $row) {
            $records[] = $this->hydrate($row);
        }

        return $records;
    }

    public function getCategoryBalance(int $accountId, int $categoryId): float
    {
        $sum = 0;

        $data = $this->pdo->query("SELECT * FROM records WHERE id_account ='{$accountId}' AND id_category ='{$categoryId}'")->fetchAll();

        foreach ($data as $row) {
            $sum += $row['money'];
        }

        return $sum;

    }

    public function findAccountById(int $accountId): array
    {
        $data = $this->pdo->query("SELECT * FROM records WHERE id_account = '{$accountId}'")->fetchAll();

        $records = [];

        foreach ($data as $row) {
            $records[] = $this->hydrate($row);
        }

        return $records;
    }

    public function findByCategoryId(int $categoryId): array
    {
        $data = $this->pdo->query("SELECT * FROM records WHERE id_category = '{$categoryId}'")->fetchAll();

        $records = [];

        foreach ($data as $row) {
            $records[] = $this->hydrate($row);
        }

        return $records;
    }

    public function findByAccountIdAndCategoryId(int $accountId, int $categoryId): array
    {
        $data = $this->pdo->query("SELECT * FROM records WHERE id_account ='{$accountId}' AND id_category ='{$categoryId}'")->fetchAll();

        $records = [];

        foreach ($data as $row) {
            $records[] = $this->hydrate($row);
        }

        return $records;
    }

    public function findAllSortedByDateDesc(): array
    {
        $data = $this->pdo->query("SELECT * FROM records ORDER BY `date` DESC")->fetchAll();

        $records = [];

        foreach ($data as $row) {
            $records[] = $this->hydrate($row);
        }

        return $records;
    }

    public function findAccountRecordsByDate(int $accountId, string $date): array
    {
        $newDate = new DateTime($date);
        $data = $this->pdo->query("SELECT * FROM records WHERE id_account ='{$accountId}' AND DATE_FORMAT(date,'%Y-%m')='{$newDate->format('Y-m')}'")->fetchAll();

        $records = [];

        foreach ($data as $row) {
            $records[] = $this->hydrate($row);
        }

        return $records;
    }

    public function findRecordsByDate($date): array
    {
        $newDate = new DateTime($date);
        $data = $this->pdo->query("SELECT * FROM records WHERE DATE_FORMAT(date,'%Y-%m')='{$newDate->format('Y-m')}'")->fetchAll();

        $records = [];

        foreach ($data as $row) {
            $records[] = $this->hydrate($row);
        }

        return $records;
    }

    public function findAccountRecordsByWeek(int $accountId): array
    {

        $data = $this->pdo->query("SELECT * FROM records WHERE id_account ='{$accountId}' AND `date` > now()-interval 7 day")->fetchAll();

        $records = [];

        foreach ($data as $row) {
            $records[] = $this->hydrate($row);
        }

        return $records;
    }

    public function findRecordsBetweenDates(int $accountId, string $fistDate, string $secondDate): array
    {
        $fist = new DateTime($fistDate);
        $second = new DateTime($secondDate);
        $data = $this->pdo->query("SELECT * FROM records WHERE id_account ='{$accountId}' AND DATE_FORMAT(date,'%Y-%m') BETWEEN '{$fist->format('Y-m')}' AND '{$second->format('Y-m')}'")->fetchAll();

        $records = [];

        foreach ($data as $row) {
            $records[] = $this->hydrate($row);
        }

        return $records;
    }

}