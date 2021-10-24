<?php

namespace Waxwink\Accounting;

use Waxwink\Accounting\Contracts\TransactionPayloadInterface;

class TransactionPayload
{
    protected ?int $creditor = null;

    protected ?int $debtor = null;

    protected ?int $amount = null;

    protected string $description = "";

    protected string $debtorDescription = "";

    protected string $creditorDescription = "";

    protected ?int $ref_id = null;

    protected ?int $voucher_id = null;

    public function getCreditor(): string
    {
        return $this->creditor;
    }

    public function setCreditor(int $creditor): static
    {
        $this->creditor = $creditor;

        return $this;
    }

    public function getDebtor(): string
    {
        return $this->debtor;
    }

    public function setDebtor(int $debtor): static
    {
        $this->debtor = $debtor;

        return $this;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDebtorDescription(): string
    {
        return $this->debtorDescription;
    }

    public function setDebtorDescription(string $debtorDescription): static
    {
        $this->debtorDescription = $debtorDescription;

        return $this;
    }

    public function getCreditorDescription(): string
    {
        return $this->creditorDescription;
    }

    public function setCreditorDescription(string $creditorDescription): static
    {
        $this->creditorDescription = $creditorDescription;

        return $this;
    }

    public function getRef(): int
    {
        return $this->ref_id;
    }

    public function setRef(?int $ref_id): static
    {
        $this->ref_id = $ref_id;

        return $this;
    }

    public function getVoucherId(): ?int
    {
        return $this->voucher_id;
    }

    public function setVoucherId(?int $voucher_id): static
    {
        $this->voucher_id = $voucher_id;

        return $this;
    }

//    /**
//     * TransactionPayload constructor.
//     *
//     * @param $creditor
//     * @param $debtor
//     * @param $amount
//     * @param $description
//     * @param $debtorDescription
//     * @param $creditorDescription
//     * @param $ref_id
//     * @param $voucher_id
//     * @param $externalKey
//     */
//    public function __construct(
//        $creditor,
//        $debtor,
//        $amount,
//        $description,
//        $debtorDescription,
//        $creditorDescription,
//        $ref_id,
//        $voucher_id,
//        $externalKey
//    ) {
//        $this->creditor            = $creditor;
//        $this->debtor              = $debtor;
//        $this->amount              = $amount;
//        $this->description         = $description;
//        $this->debtorDescription   = $debtorDescription;
//        $this->creditorDescription = $creditorDescription;
//        $this->ref_id              = $ref_id;
//        $this->voucher_id          = $voucher_id;
//        $this->externalKey         = $externalKey;
//    }

    public function toArray(): array
    {
        return [
            "creditor"            => $this->creditor,
            "debtor"              => $this->debtor,
            "amount"              => $this->amount,
            "description"         => $this->description,
            "debtorDescription"   => $this->debtorDescription,
            "creditorDescription" => $this->creditorDescription,
            "ref_id"              => $this->ref_id,
            "voucher_id"          => $this->voucher_id,
        ];
    }

    public function sharedArray(): array
    {
        return [
            "ref_id"      => $this->ref_id,
            "voucher_id"  => $this->voucher_id,
        ];
    }

}
