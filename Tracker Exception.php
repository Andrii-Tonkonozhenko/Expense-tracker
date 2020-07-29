<?php

class ExpenseTrackerException extends Exception
{

}

class InsufficientFundsInTheAccount extends ExpenseTrackerException
{
    protected $message = 'Insufficient funds in the account';
}

class AccountNotFound extends ExpenseTrackerException
{
    protected $message = 'Account not found';
}