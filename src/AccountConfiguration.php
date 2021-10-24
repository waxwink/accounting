<?php

namespace Waxwink\Accounting;

class AccountConfiguration
{
    protected int $bank;

    protected int $revenue;

    protected int $expense;

    protected int $tax;


    /**
     * @return int
     */
    public function getBank(): int
    {
        return $this->bank;
    }

    /**
     * @return int
     */
    public function getRevenue(): int
    {
        return $this->revenue;
    }

    /**
     * @return int
     */
    public function getExpense(): int
    {
        return $this->expense;
    }

    /**
     * @return int
     */
    public function getTax(): int
    {
        return $this->tax;
    }

    public function set($key, $accountId)
    {
        $this->$key = $accountId;
    }
}
