<?php

interface RecordRepositoryInterface
{
    public function createRecord(Record $record): void;

    public function getAllRecords(): array;

    public function getCategoryBalance(int $accountId, int $categoryId): float;

    public function findAccountById(int $accountId): array;

    public function findByCategoryId(int $categoryId): array;

    public function findAllSortedByDateDesc(): array;

    public function findAccountRecordsByDate(int $accountId, string $date): array;

    public function findRecordsByDate($date): array;

    public function findAccountRecordsByWeek(int $accountId, string $date): array;

    public function findAccountRecordsByDateAndDate(int $accountId, string $fistDate, string $secondDate): array;

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

    public function findAccountRecordsByDateAndDate(int $accountId, string $fistDate, string $secondDate): array
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

    public function findAccountRecordsByWeek(int $accountId, string $date): array
    {
        $records = [];

        foreach ($this->records as $record) {
            if ($record->getAccountId() === $accountId && date('y m W',
                    strtotime($date)) === $record->getDate()->format('y m W')) {
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