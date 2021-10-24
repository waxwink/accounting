<?php

namespace Waxwink\Accounting\Concerns;

use Waxwink\Accounting\Contracts\TransactionPayloadInterface;
use Waxwink\Accounting\Contracts\TransactionRecordInterface;
use Waxwink\Accounting\Contracts\VoucherRepositoryInterface;
use Waxwink\Accounting\Exceptions\InvalidPayloadException;

trait InsertsRecords
{

    /**
     * @throws InvalidPayloadException
     */
    protected function insertRecords(): void
    {
        $this->setVoucherId();
        $this->createRecord($this->debtAttributes());
        $this->createRecord($this->creditAttributes());
    }

    protected function createRecord(array $attributes): TransactionRecordInterface
    {
        return $this->transactionRepository->createRecord($attributes);
    }

    /**
     * @throws InvalidPayloadException
     */
    protected function debtAttributes(): array
    {
        return array_merge($this->onlyDebtAttributes(), $this->transactionPayload->sharedArray());
    }

    /**
     * @throws InvalidPayloadException
     */
    protected function creditAttributes(): array
    {
        return array_merge($this->onlyCreditAttributes(), $this->transactionPayload->sharedArray());
    }

    /**
     * @throws InvalidPayloadException
     */
    protected function onlyDebtAttributes(): array
    {
        return [
            "account_id"  => $this->getFromPayloadOrFail("debtor"),
            "debt"        => $this->getFromPayloadOrFail("amount"),
            "credit"      => 0,
            "description" => $this->getFromPayload("debtorDescription") ??
                $this->getFromPayloadOrFail("description"),
        ];
    }

    /**
     * @throws InvalidPayloadException
     */
    protected function onlyCreditAttributes(): array
    {
        return [
            "account_id"  => $this->getFromPayloadOrFail("creditor"),
            "debt"        => 0,
            "credit"      => $this->getFromPayloadOrFail("amount"),
            "description" => $this->getFromPayload("creditDescription") ??
                $this->getFromPayloadOrFail("description"),
        ];
    }

    /**
     * @throws InvalidPayloadException
     */
    protected function getFromPayloadOrFail(string $string)
    {
        if (!$value = $this->getFromPayload($string)) {
            throw new InvalidPayloadException("$string is not set in the payload");
        }

        return $value;
    }

    protected function getFromPayload(string $string)
    {
        $method = "get" . ucfirst($string);

        return $this->transactionPayload->$method();
    }

    protected function setVoucherId(): void
    {
        if (!$this->isVoucherIdValid($this->transactionPayload->getVoucherId())) {
            $this->transactionPayload->setVoucherId($this->voucherRepository->create()->getKey());
        }
    }

    /**
     * @param string $voucherId
     *
     * @return bool
     */
    protected function isVoucherIdValid(string $voucherId): bool
    {
        return $voucherId && $this->voucherRepository->exists($voucherId);
    }

}