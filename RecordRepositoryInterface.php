<?php

interface RecordRepositoryInterface
{
    public function addReplenishment(Record $record): void;

    public function addCosts(Record $record): void;

}

class InMemoryRecordRepository implements RecordRepositoryInterface
{
    private $records = [];

    public function getAccountBalance(Account $account): void
    {
        echo $account->getBalance();
    }

    public function addReplenishment(Record $record): void
    {
        $record->setId(count($this->records) + 1);
        $this->records[] = $record;
    }

    public function addCosts(Record $record): void
    {
        // $record->setId(count($this->records) + 1); НО це теж має по суті бути тут, чи як воопще це має працювати.
        $this->records[] = $record;
    }

    public function getRecord(Account $id_account, Category $id_category)
    {

    }
}