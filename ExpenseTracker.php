<?php

require 'models/Account.php';
require 'models/Category.php';
require 'models/Record.php';
require 'repositories/AccountRepositoryInterface.php';
require 'repositories/CategoryRepositoryInterface.php';
require 'repositories/RecordRepositoryInterface.php';
require 'exception/TrackerException.php';

class ExpenseTracker
{
    private $accountRepository;
    private $recordRepository;
    private $categoryRepository;

    public function __construct(
        AccountRepositoryInterface $account,
        RecordRepositoryInterface $recordRepository,
        CategoryRepositoryInterface $categoryRepository
    ) {
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

    public function showAccountBalance(int $accountId): void
    {
        $this->accountRepository->getAccountBalance($accountId);
    }

    public function showBalanceOfAllAccounts(): void
    {
        $this->accountRepository->getBalanceOfAllAccounts();
    }

    public function addReplenishment(int $id_account, int $id_category, float $money, $date): void
    {
        $this->accountRepository->update($this->accountRepository->getAccount($id_account), $money);
        $record = new Record($id_account, $id_category, $money, $date);
        $this->recordRepository->addRecord($record);
    }

    public function addExpence(int $id_account, int $id_category, float $money, $date): void
    {
        $this->accountRepository->update($this->accountRepository->getAccount($id_account), $money);
        $record = new Record($id_account, $id_category, $money, $date);
        $this->recordRepository->addRecord($record);
    }

    public function showRecords(array $records): void
    {
        foreach ($records as $record) {
            $account = $this->accountRepository->getAccount($record->getAccountId());
            $category = $this->categoryRepository->getCategory($record->getCategoryId());

            echo 'Account: ' . $account->getTitle() . "</br>";
            echo 'Category: ' . $category->getTitle();
            echo $this->accountRepository->getPrettierMoney($record->getMoney());
            echo 'Data: ' . $record->getDate() . "</br>" . "</br>";
        }
    }

    public function showAllRecords(): void
    {
        $this->showRecords($this->recordRepository->getAllRecords());
    }

    public function showRecordsByAccount(int $accountId): void
    {
        $this->showRecords($this->recordRepository->findAccountById($accountId));
    }

    public function showRecordsByCategory(int $categoryId): void
    {
        $this->showRecords($this->recordRepository->findByCategoryId($categoryId));
    }

    public function showAllCategory(): void
    {
        foreach ($this->accountRepository->getAllAccounts() as $account) {
            echo $account->getTitle() . "</br>";
            foreach ($this->categoryRepository->getAllCategory() as $category) {
                echo $category->getTitle() . ' ' . $this->recordRepository->getCategoryBalance($account->getId(),
                        $category->getId()) . '</br>';
            }
        }
    }

    public function showCategoriesBalanceByAccountId(int $id): void
    {
        foreach ($this->categoryRepository->getAllCategory() as $category) {
            echo $category->getTitle() . ' ' . $this->recordRepository->getCategoryBalance($id,
                    $category->getId()) . '</br>';
        }
    }

}

$account = new InMemoryAccountRepository();
$recordRepository = new InMemoryRecordRepository();
$categoryRepository = new InMemoryCategoryRepository();
$expense_tracker = new ExpenseTracker($account, $recordRepository, $categoryRepository);


try {
    $expense_tracker->addCategory('Income');
    $expense_tracker->addCategory('Food');
    $expense_tracker->addCategory('Purchases');
    $expense_tracker->addCategory('Transport');
    $expense_tracker->addCategory('Other');

    $expense_tracker->addAccount('Study', 250);
    $expense_tracker->addAccount('Family', 444);
    $expense_tracker->addAccount('Andrii', 33.33);


    $expense_tracker->addReplenishment(1, 1, 22.7, (date("dS of F  h:I:s A ")));
    $expense_tracker->addReplenishment(1, 1, 24.3, (date("dS of F  h:I:s A ")));
    $expense_tracker->addExpence(3, 2, -42.2, (date("dS of F  h:I:s A ")));
    $expense_tracker->addReplenishment(2, 1, 2, (date("dS of F  h:I:s A ")));
    $expense_tracker->addReplenishment(2, 1, 1.75, (date("dS of F  h:I:s A ")));
    $expense_tracker->addExpence(1, 4, -77, (date("dS of F  h:I:s A ")));
    $expense_tracker->addReplenishment(2, 5, 155, (date("dS of F  h:I:s A ")));


//    $expense_tracker->getAccountBalance(3);
    $expense_tracker->showBalanceOfAllAccounts();
//    $expense_tracker->showAllRecords();
//    $expense_tracker->showRecordsByAccount(10);
//    $expense_tracker->showRecordsByCategory(10);
    $expense_tracker->showAllCategory();
//    $expense_tracker->showCategoriesBalanceByAccountId(2);
} catch (ExpenseTrackerException $e) {
    die($e->getMessage());
}

