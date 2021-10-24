<?php

namespace Waxwink\Accounting;

use Waxwink\Accounting\Contracts\HasAccount;

class AccountingService
{
    const BANK_ACCOUNT = "bank";
    const REVENUE_ACCOUNT = "revenue";
    const EXPENSE_ACCOUNT = "expense";
    const TAX_ACCOUNT = "tax";

    protected bool $withLock = false;

    public function __construct(
        protected TransactionService $transactionService,
        protected AccountConfiguration $accountConfig
    ) {
    }

    public function balance(HasAccount $user): int
    {
        return $this->transactionService
            ->withLock($user->getAccountId(), $this->withLock)
            ->balance($user->getAccountId());
    }

    /**
     * @throws Exceptions\InvalidPayloadException
     * @throws Exceptions\TransactionFailedException
     */
    public function deposit(HasAccount $user, $amount, ?int $ref = null): ?bool
    {
        return $this->transactionService
            ->from($user->getAccountId())
            ->to($this->accountConfig->getBank())
            ->debtorDescription("deposit " . $user->getAccountId())
            ->creditorDescription("deposit")
            ->amount($amount)
            ->ref($ref)
            ->withLock($user->getAccountId(), $this->withLock)
            ->transfer();
    }

    /**
     * @throws Exceptions\TransactionFailedException
     * @throws Exceptions\InvalidPayloadException
     */
    public function withdraw(HasAccount $user, $amount, ?int $ref = null): ?bool
    {
        return $this->transactionService
            ->from($this->accountConfig->getBank())
            ->to($user->getAccountId())
            ->creditorDescription("deposit " . $user->getAccountId())
            ->debtorDescription("deposit")
            ->amount($amount)
            ->ref($ref)
            ->withLock($user->getAccountId(), $this->withLock)
            ->transfer();
    }

    /**
     * @throws Exceptions\TransactionFailedException
     * @throws Exceptions\InvalidPayloadException
     */
    public function pay(HasAccount $user, int $amount, ?int $ref = null): ?bool
    {
        return $this->transactionService
            ->from($this->accountConfig->getRevenue())
            ->to($user->getAccountId())
            ->amount($amount)
            ->creditorDescription($user->getAccountId())
            ->debtorDescription("payment")
            ->ref($ref)
            ->withLock($user->getAccountId(), $this->withLock)
            ->transfer();
    }

    public function payTax(HasAccount $user, int $amount, ?int $ref = null): ?bool
    {
        return $this->transactionService
            ->from($this->accountConfig->getTax())
            ->to($user->getAccountId())
            ->amount($amount)
            ->creditorDescription($user->getAccountId())
            ->debtorDescription("tax")
            ->ref($ref)
            ->withLock($user->getAccountId(), $this->withLock)
            ->transfer();
    }

    public function payTo(HasAccount $user, int $amount, ?int $ref = null): ?bool
    {
        return $this->transactionService
            ->from($user->getAccountId())
            ->to($this->accountConfig->getExpense())
            ->amount($amount)
            ->creditorDescription("payment")
            ->debtorDescription($user->getAccountId())
            ->ref($ref)
            ->withLock($user->getAccountId(), $this->withLock)
            ->transfer();
    }

    /**
     * @throws Exceptions\TransactionFailedException
     * @throws Exceptions\InvalidPayloadException
     */
    public function refund(HasAccount $account, $amount, ?int $ref = null): ?bool
    {
        return $this->transactionService
            ->from($account->getAccountId())
            ->to($this->accountConfig->getRevenue())
            ->amount($amount)
            ->debtorDescription($account->getAccountId())
            ->creditorDescription("refund")
            ->ref($ref)
            ->withLock($account->getAccountId(), $this->withLock)
            ->transfer();
    }

    public function bankBalance(): int
    {
        return $this->transactionService
            ->withLock($this->accountConfig->getBank(), $this->withLock)
            ->balance($this->accountConfig->getBank());
    }

    public function revenueBalance(): int
    {
        return $this->transactionService
            ->withLock($this->accountConfig->getRevenue(), $this->withLock)
            ->balance($this->accountConfig->getRevenue());
    }

    public function revenueBalanceByRef($ref): int
    {
        return $this->transactionService
            ->withLock($this->accountConfig->getRevenue(), $this->withLock)
            ->balance($this->accountConfig->getRevenue(), $ref);
    }

    public function expenseBalance(): int
    {
        return $this->transactionService
            ->withLock($this->accountConfig->getExpense(), $this->withLock)
            ->balance($this->accountConfig->getExpense());
    }

    public function expenseBalanceByRef($ref): int
    {
        return $this->transactionService
            ->withLock($this->accountConfig->getExpense(), $this->withLock)
            ->balance($this->accountConfig->getExpense(), $ref);
    }

    public function taxBalance(): int
    {
        return $this->transactionService
            ->withLock($this->accountConfig->getTax(), $this->withLock)
            ->balance($this->accountConfig->getTax());
    }

    public function taxBalanceByRef($ref): int
    {
        return $this->transactionService
            ->withLock($this->accountConfig->getTax(), $this->withLock)
            ->balance($this->accountConfig->getTax(), $ref);
    }


    public function transactionsList(HasAccount $user, ...$options): \ArrayAccess
    {
        return $this->transactionService->transactionsList($user->getAccountId(), $options);
    }


    public function bankTransactionsList(...$options): \ArrayAccess
    {
        return $this->transactionService->transactionsList($this->accountConfig->getBank(), $options);
    }

    public function revenueTransactionsList(...$options): \ArrayAccess
    {
        return $this->transactionService->transactionsList($this->accountConfig->getRevenue(), $options);
    }

    public function expenseTransactionsList(...$options): \ArrayAccess
    {
        return $this->transactionService->transactionsList($this->accountConfig->getExpense(), $options);
    }

    public function withLock(): static
    {
        $this->withLock = true;

        return $this;
    }

    public function withoutLock(): static
    {
        $this->withLock = false;

        return $this;
    }

}
