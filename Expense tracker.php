<?php

require 'Account.php';
require 'AccountRepository.php';
require 'Record.php';
require 'RecordRepositoryInterface.php';
require 'Category.php';
require 'CategoryRepository.php';
require 'Tracker Exception.php';

class ExpenseTracker
{
    private $accountRepository;
    private $recordRepository;
    private $categoryRepository;

    public function __construct(InMemoryAccountRepository $account, RecordRepositoryInterface $recordRepository, CategoryRepository $categoryRepository)
    {
        $this->accountRepository = $account;
        $this->recordRepository = $recordRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function addAccount(string $accountName, float $accountBalance): void
    {
        $account = new Account();
        $account->setTitle($accountName);
        $account->setAccountBalance($accountBalance);
        $this->accountRepository->createAccount($account);
    }

    public function addCategory(string $categoryTitle): void
    {
        $category = new Category();
        $category->setTitle($categoryTitle);
        $this->categoryRepository->addCategory($category);
    }

    public function getAccountBalance(int $id_account): void
    {
        $this->recordRepository->getAccountBalance($this->accountRepository->getAccount($id_account));
    }

    public function addReplenishment(int $id_account, int $id_category, float $money, $date) : void
    {
        $this->accountRepository->update($id_account, $money);
        $record = new Record($id_account, $id_category, $money, $date);
        $this->recordRepository->addReplenishment($record);
    }

    public function addCosts(int $id_account,  int $id_category, float $money, $date) : void
    {
        $record = new Record($id_account, $id_category, $money, $date);
        $this->recordRepository->addReplenishment($record);
    }

}

$account = new InMemoryAccountRepository();
$recordRepository = new InMemoryRecordRepository();
$categoryRepository = new CategoryRepository();
$expense_tracker = new ExpenseTracker($account, $recordRepository, $categoryRepository);


try {
    $expense_tracker->addCategory('Income');
    $expense_tracker->addCategory('Food');
    $expense_tracker->addCategory('Purchases');
    $expense_tracker->addCategory('Transport');
    $expense_tracker->addCategory('Other');

    $expense_tracker->addAccount('Study',250);
    $expense_tracker->addAccount('Family',444);
    $expense_tracker->addAccount('Andrii',33.33);


    $expense_tracker->addReplenishment(1,1,22.7, (date("dS of F  h:I:s A ")));

    $expense_tracker->addReplenishment(2,1,1.75, (date("dS of F  h:I:s A ")));
    $expense_tracker->getAccountBalance(2);

} catch (ExpenseTrackerException $e) {
    die($e->getMessage());
}

