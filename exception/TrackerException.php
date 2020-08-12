<?php

class ExpenseTrackerException extends Exception
{

}

class AccountNotFoundException extends ExpenseTrackerException
{
    protected $message = 'Account not found';
}

class CategoryNotFoundException extends ExpenseTrackerException
{
    protected $message = 'Category not found';
}