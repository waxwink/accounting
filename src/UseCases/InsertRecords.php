<?php

namespace Waxwink\Accounting\UseCases;

use Waxwink\Accounting\Contracts\TransactionRecordInterface;
use Waxwink\Accounting\Contracts\TransactionRepositoryInterface;
use Waxwink\Accounting\Contracts\VoucherRepositoryInterface;
use Waxwink\Accounting\Exceptions\InvalidPayloadException;
use Waxwink\Accounting\TransactionPayload;

class InsertRecords
{
    public function __construct(
        protected VoucherRepositoryInterface $voucherRepository,
        protected TransactionPayload $transactionPayload,
        protected TransactionRepositoryInterface $transactionRepository
    ) {
    }

    /**
     * @throws InvalidPayloadException
     */
    public function fire(): void
    {
        $this->setVoucherId();
        $this->createRecord($this->debtAttributes());
        $this->createRecord($this->creditAttributes());
    }

    public function getTransactionPayload(): TransactionPayload
    {
        return $this->transactionPayload;
    }

    public function setTransactionPayload(TransactionPayload $transactionPayload): static
    {
        $this->transactionPayload = $transactionPayload;

        return $this;
    }

    protected function setVoucherId(): void
    {
        if (!$this->isVoucherIdValid($this->transactionPayload->getVoucherId())) {
            $this->transactionPayload->setVoucherId($this->voucherRepository->create()->getKey());
        }
    }

    protected function isVoucherIdValid(?int $voucherId): bool
    {
        return $voucherId && $this->voucherRepository->exists($voucherId);
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
            "description" => $this->getFromPayload("creditorDescription") ??
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

}
