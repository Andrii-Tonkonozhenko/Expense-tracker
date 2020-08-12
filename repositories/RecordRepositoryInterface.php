<?php

interface RecordRepositoryInterface
{
    public function addRecord(Record $record): void;

    public function getAllRecords(): array;

    public function getCategoryBalance(int $accountId, int $categoryId): float;

    public function findAccountById(int $accountId): array;

    public function findByCategoryId(int $categoryId): array;

    public function getCategoryBalanceByAccountId(int $accountId, int $categoryId);

}

class InMemoryRecordRepository implements RecordRepositoryInterface
{
    private $records = [];

    public function addRecord(Record $record): void
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

    public function findByAccountIdAndCategoryId(int $accountId, int $categoryId ): array
    {
        $records = [];

        foreach ($this->records as $record) {
            if ($record->getAccountId() === $accountId && $record->getCategoryId() === $categoryId) {
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

    public function getCategoryBalanceByAccountId(int $accountId, int $categoryId)
    {

    }

}