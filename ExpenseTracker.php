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

    private function showRecords(array $records): void
    {
        foreach ($records as $record) {
            $account = $this->accountRepository->getAccount($record->getAccountId());
            $category = $this->categoryRepository->getCategory($record->getCategoryId());

            echo 'Account: ' . $account->getTitle() . "</br>";
            echo 'Category: ' . $category->getTitle();
            echo $this->accountRepository->getPrettierMoney($record->getMoney());
            echo 'Data: ' . $record->getDate()->format('Y-m-d H:i:s') . "</br>" . "</br>";
        }
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
        $this->categoryRepository->createCategory($category);
    }

    public function showAccountBalance(int $accountId): void
    {
        echo $this->accountRepository->getAccountBalance($accountId) . "</br>";
    }

    public function showBalanceOfAllAccounts(): void
    {
        echo $this->accountRepository->getBalanceOfAllAccounts() . "</br>";
    }

    public function addRecord(int $id_account, int $id_category, float $money, DateTime $date): void
    {
        $this->accountRepository->update($this->accountRepository->getAccount($id_account), $money);
        $record = new Record($id_account, $id_category, $money, $date);
        $this->recordRepository->createRecord($record);
    }

    public function showAllOrderedByDateDesc(): void
    {
        $this->showRecords($this->recordRepository->findAllSortedByDateDesc());
    }
//
    public function showAllRecords(): void
    {
        $this->showRecords($this->recordRepository->getAllRecords());
    }

    public function showRecordsByAccountAndCategory(int $accountId, int $categoryId): void
    {
        $this->showRecords($this->recordRepository->findByAccountIdAndCategoryId($accountId, $categoryId));
    }

    public function showRecordsByDate(string $date): void
    {
        $this->showRecords($this->recordRepository->findRecordsByDate($date));
    }

    public function showAccountRecordsByDate(int $accountId, string $date): void
    {
        $this->showRecords($this->recordRepository->findAccountRecordsByDate($accountId, $date));
    }

    public function showAccountRecordsByWeek(int $accountId): void
    {
        $this->showRecords($this->recordRepository->findAccountRecordsByWeek($accountId));
    }
//
    public function showRecordsBetweenDates(int $accountId, string $fistDate, string $secondDate): void
    {
        $this->showRecords($this->recordRepository->findRecordsBetweenDates($accountId, $fistDate, $secondDate));
    }

    public function showRecordsByAccount(int $accountId): void
    {
        $this->showRecords($this->recordRepository->findAccountById($accountId));
    }

    public function showRecordsByCategory(int $categoryId): void
    {
        $this->showRecords($this->recordRepository->findByCategoryId($categoryId));
    }

    public function showAllCategoriesBalance(): void
    {
        foreach ($this->accountRepository->getAllAccounts() as $account) {
            echo "</br>";
            echo $account->getTitle() . "</br>";
            echo "</br>";
            foreach ($this->categoryRepository->getAllCategory() as $category) {
                echo $category->getTitle() . ' ' . $this->recordRepository->getCategoryBalance($account->getId(),
                        $category->getId()) . '</br>';
            }
        }
    }

    public function showCategoriesBalanceByAccountId(int $accountId): void
    {
        foreach ($this->categoryRepository->getAllCategory() as $category) {
            echo $category->getTitle() . ' ' . $this->recordRepository->getCategoryBalance($accountId,
                    $category->getId()) . '</br>';
        }
    }
}
$pdo = new PDO('mysql:host=localhost;dbname=expensetracker', 'root', '');
//$accountRepository = new MySQLAccountRepository($pdo);
////$categoryRepository = new MySQLCategoryRepository($pdo);
//$recordRepository = new MySQLRecordRepository($pdo);
$accountRepository = new InMemoryAccountRepository();
$categoryRepository = new InMemoryCategoryRepository();
$recordRepository = new InMemoryRecordRepository();
$expense_tracker = new ExpenseTracker($accountRepository, $recordRepository, $categoryRepository);

try {
    $expense_tracker->addCategory('Income');
    $expense_tracker->addCategory('Food');
    $expense_tracker->addCategory('Purchases');
    $expense_tracker->addCategory('Transport');
    $expense_tracker->addCategory('Other');

    $expense_tracker->addAccount('Study', 250);
    $expense_tracker->addAccount('Family', 444);
    $expense_tracker->addAccount('Andrii', 33.33);

    $expense_tracker->addRecord(1, 1, 22.7, new DateTime('2020-08-12 17:16:17'));
    $expense_tracker->addRecord(1, 1, 22.7, new DateTime('2020-07-15 17:16:17'));
    $expense_tracker->addRecord(1, 1, 22.7, new DateTime('2019-07-15 17:16:17'));
    $expense_tracker->addRecord(3, 2, -42.2, new DateTime('2019-07-15 14:15:17'));
    $expense_tracker->addRecord(2, 1, 2, new DateTime('2020-06-15 18:16:17'));
    $expense_tracker->addRecord(2, 1, 1.75, new DateTime('2018-05-15 12:16:17'));
    $expense_tracker->addRecord(1, 4, -77, new DateTime('2019-05-15 10:40:16'));
    $expense_tracker->addRecord(2, 5, 155, new DateTime('2019-05-15 09:20:17'));
    $expense_tracker->addRecord(2, 5, 155, new DateTime('NOW'));
//$expense_tracker->test();
//    $expense_tracker->showAccountBalance(2);
//    $expense_tracker->showBalanceOfAllAccounts();
//    $expense_tracker->showAllRecords();
//    $expense_tracker->showRecordsByAccount(1);
//    $expense_tracker->showRecordsByCategory(1);
//    $expense_tracker->showAllCategoriesBalance();
//    $expense_tracker->showRecordsByAccountAndCategory(1,1);
//    $expense_tracker->showCategoriesBalanceByAccountId(3);
//    $expense_tracker->showRecordsByDate('2020-07-11');
//    $expense_tracker->showAccountRecordsByDateAndDate(1,'2019-00-00','2021-00-00' );
//    $expense_tracker->showAccountRecordsByWeek(2);
    $expense_tracker->showAccountRecordsByDate(1,'2020-07-15');
//    $expense_tracker->showAllOrderedByDateDesc();
} catch (ExpenseTrackerException $e) {
    die($e->getMessage());
}

