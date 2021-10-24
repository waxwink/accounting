<?php

namespace Waxwink\Accounting\Contracts;

interface TransactionPayloadInterface
{
    public function getCreditor(): string;

    public function setCreditor(string $creditor): static;

    public function getDebtor(): string;

    public function setDebtor(string $debtor): static;

    public function getAmount(): string;

    public function setAmount(string $amount): static;

    public function getDescription(): string;

    public function setDescription(string $description): static;

    public function getDebtorDescription(): string;

    public function setDebtorDescription(string $debtorDescription): static;

    public function getCreditorDescription(): string;

    public function setCreditorDescription(string $creditorDescription): static;

    public function getRef(): string;

    public function setRef(string $ref_id): static;

    public function getVoucherId(): string;

    public function setVoucherId(string $voucher_id): static;

    public function getExternalKey(): string;

    public function setExternalKey(string $externalKey): static;

    public function toArray(): array;

    public function sharedArray(): array;

}