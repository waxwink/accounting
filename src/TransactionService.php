<?php

namespace Waxwink\Accounting;

use Waxwink\Accounting\Concerns\HasPayload;
use Waxwink\Accounting\Contracts\TransactionRepositoryInterface;
use Waxwink\Accounting\Exceptions\InvalidPayloadException;
use Waxwink\Accounting\Exceptions\LockedAccountException;
use Waxwink\Accounting\Exceptions\TransactionFailedException;
use Waxwink\Accounting\UseCases\InsertRecords;

class TransactionService
{
    use HasPayload;

    protected bool $withLock = false;

    protected ?int $lockedAccountId = null;

    public function __construct(
        protected TransactionRepositoryInterface $transactionRepository,
        protected LockHandler $lockHandler,
        protected InsertRecords $insertRecords,
        TransactionPayload $transactionPayload
    ) {
        $this->setTransactionPayload($transactionPayload);
    }

    /**
     * @throws LockedAccountException
     */
    public function transfer(): mixed
    {
        return $this->lockHandler->handle(
            fn() => $this->transactionalInsert(),
            $this->lockedAccountId,
            $this->withLock);
    }

    /**
     * @throws LockedAccountException
     */
    public function balance(int $accountId, mixed $ref = null): int
    {
        return $this->lockHandler->handle(
            fn() => $this->transactionRepository->balance($accountId, $ref),
            $this->lockedAccountId,
            $this->withLock);
    }

    public function transactionsList(int $accountId, array $options): \ArrayAccess
    {
        return $this->transactionRepository->findByAccount($accountId, $options);
    }

    public function withLock(int $accountId, bool $withLock = true): static
    {
        $this->withLock        = $withLock;
        $this->lockedAccountId = $accountId;

        return $this;
    }

    /**
     * @throws TransactionFailedException
     * @throws InvalidPayloadException
     */
    protected function transactionalInsert(): mixed
    {
        try {
            return $this->transactionRepository->transactional(
                fn() => $this->insertRecords->setTransactionPayload($this->transactionPayload)->fire());
        } catch (InvalidPayloadException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            throw new TransactionFailedException($exception->getMessage());
        }
    }


}
