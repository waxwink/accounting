<?php

namespace Waxwink\Accounting\Concerns;


use Waxwink\Accounting\TransactionPayload;

trait HasPayload
{
    protected TransactionPayload $transactionPayload;

    public function getTransactionPayload(): TransactionPayload
    {
        return $this->transactionPayload;
    }

    /**
     * @param TransactionPayload $transactionPayload
     */
    public function setTransactionPayload(TransactionPayload $transactionPayload): void
    {
        $this->transactionPayload = $transactionPayload;
    }

    public function from(int $from): static
    {
        $this->transactionPayload->setCreditor($from);

        return $this;
    }

    public function to(int $to): static
    {
        $this->transactionPayload->setDebtor($to);

        return $this;
    }

    public function amount(int $amount): static
    {
        $this->transactionPayload->setAmount($amount);

        return $this;
    }

    public function debtorDescription(string $string): static
    {
        $this->transactionPayload->setDebtorDescription($string);

        return $this;
    }

    public function creditorDescription(string $string): static
    {
        $this->transactionPayload->setCreditorDescription($string);

        return $this;
    }

    public function description(string $string): static
    {
        $this->transactionPayload->setDescription($string);

        return $this;
    }

    public function ref(?int $ref): static
    {
        $this->transactionPayload->setRef($ref);

        return $this;
    }
}
